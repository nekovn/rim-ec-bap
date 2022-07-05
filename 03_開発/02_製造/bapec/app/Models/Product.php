<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Util\SystemHelper;
use App\Enums\TaxTypeDefine;
use App\Enums\CodeDefine;
use App\Cart\Contracts\Buyable;
use App\Cart\CanBeBought;
use App\Models\Relations\TaxTrait;

class Product extends Model implements Buyable
{
    use CanBeBought;
    use TaxTrait;

    
    /**
     * Get the unitprice of the Buyable item.
     *
     * @return float
     */
    public function getBuyableUnitPrice($options = null)
    {
        return $this->unit_price;
    }

    // relation
    // 消費税モデルは常にロード
    protected $with = ['tax'];

    public function tax()
    {
        return $this->hasTax(\App\Models\Tax::class);
    }


    /**
     * 消費税種類の名称を返す
     *
     * @return string 消費税種類の名称
     */
    public function getTaxKindValueAttribute()
    {
        $a = SystemHelper::getCodes(CodeDefine::TAX_KIND);
        return SystemHelper::getCodes(CodeDefine::TAX_KIND)[$this->tax_kind];
    }
    /**
     * 消費税区分の名称を返す
     *
     * @return string 消費税区分の名称
     */
    public function getTaxTypeValueAttribute()
    {
        return SystemHelper::getCodes(CodeDefine::TAX_TYPE)[$this->tax_type];
    }

    // local scope
    /**
     * 公開中の条件
     *
     * @param $query
     * @return mixed
     */
    public function scopePublish($query){
        return $query->where('is_published', 1)
                        ->where(function ($query) {
                            $query->whereNull('sales_start_datetime')
                                ->orWhere('sales_start_datetime', '<=', date('Y-m-d H:i:s'));
                        })
                        ->where(function ($query) {
                            $query->whereNull('end_at')
                                ->orWhere('end_at', '>=', date('Y-m-d H:i:s'));
                        });
    }

    /**
     * 個別に税率を取得する
     *
     * @return float 消費税率
     */
    public function getTaxRate()
    {
        $rate = 0;
        $tax = Tax::active($this->tax_kind);
        if ($tax) {
            $rate = $tax->tax_rate;
        }
        return $rate;
    }

    /**
     * 限定期間を考慮した単価を取得
     *
     * @return float 限定期間を考慮した単価
     */
    public function getLimitedUnitPrice()
    {
        // 通常単価
        $salePrice = $this->unit_price;

        // 期間限定単価
        if ($this->limited_unit_price > 0) {
            $now = Carbon::now();
            $start = new Carbon($this->limited_start_datetime);
            $end = new Carbon($this->limited_end_datetime);
            if (
                ($this->limited_start_datetime == null || ($this->limited_start_datetime && $start->lte($now)))
                    && 
                ($this->limited_end_datetime == null || ($this->limited_end_datetime && $end->gte($now)))
            ) {
                $salePrice = $this->limited_unit_price;
            }
        }
        return $salePrice;
    }

    /**
     * 割引前税抜き価格
     *
     * @return float 割引前税抜き価格
     */
    public function getSalePriceBeforeDiscount()
    {
        // 通常単価
        $salePrice = $this->getLimitedUnitPrice();
        $cartAttribute = \Cart::cartAttribute();
        // 商品単価（税抜き）
        return \Price::excludingTax($salePrice, $this->tax->tax_rate, $this->tax_type, $cartAttribute->getTaxRoundingType());
    }

    /**
     * 割引後税抜き価格
     *
     * @return float 割引後税抜き価格
     */
    public function getSalePrice()
    {
        // 通常単価
        $salePrice = $this->getSalePriceBeforeDiscount();

        // 会員ランク別割引適用
        $cartAttribute = \Cart::cartAttribute();
        if ($cartAttribute->getDiscountRate()) {
            // 割引額
            $discount = \Price::discount($salePrice, $cartAttribute->getDiscountRate(), $cartAttribute->getDiscountRoundingType());
            // 商品単価（税抜き） - 割引額
            $salePrice = bcsub($salePrice, $discount, 2);
        }

        // return \Price::rounding($salePrice, $cartAttribute->getDiscountRoundingType());
        return $salePrice;
    }

    /**
     * 税込み販売価格
     *
     * @return float 販売価格
     */
    public function getSalePriceTaxIncluded()
    {
        // 割引後税抜き価格
        $salePrice = $this->getSalePrice();

        $cartAttribute = \Cart::cartAttribute();

        $taxType = $this->tax_type;
        if (TaxTypeDefine::EXEMPT != $taxType) {
            // 非課税以外は外税で計算
            $taxType = TaxTypeDefine::EXCLUSIVE;
        }

        return \Price::includingTax($salePrice, $this->tax->tax_rate, $taxType, $cartAttribute->getTaxRoundingType());
    }

    /**
     * 割引後販売価格に対する税額
     *
     * @return float 割引後販売価格に対する税額
     */
    public function getSalePriceTax()
    {
        // 割引後税抜き価格
        $salePrice = $this->getSalePrice();
        
        $cartAttribute = \Cart::cartAttribute();

        $taxType = $this->tax_type;
        if (TaxTypeDefine::EXEMPT != $taxType) {
            // 非課税以外は外税で計算
            $taxType = TaxTypeDefine::EXCLUSIVE;
        }

        return \Price::tax($salePrice, $this->tax->tax_rate, $taxType, $cartAttribute->getTaxRoundingType());
    }

}
