<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Util\SystemHelper;
use App\Enums\TaxKindDefine;
use App\Enums\TaxTypeDefine;
use App\Enums\CodeDefine;
use App\Enums\Constants;
use App\Cart\Contracts\Buyable;
use App\Cart\CanBeBought;
use App\Enums\SaleStatusDefine;
use App\Enums\StatusDefine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Relations\TaxTrait;

/**
 * 商品テーブル Model
 */
class Goods extends Model implements Buyable
{
    use BaseTrait;
    use LeftJoinCvalueContentScope;
    use CanBeBought;
    use TaxTrait;

    // テーブル名
    protected $table = 'goods';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at'];

    protected $appends = ['image_url'];

    public static function getNextId()
    {
        $statement = DB::select("show table status like 'goods'");

        return $statement[0]->Auto_increment;
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function($goods) {
            $goods->sku_code = $goods->code;
        });

        self::saving(function($goods) {
            $goods->purchase_unit_price = is_null($goods->purchase_unit_price) ? '0.00' : $goods->purchase_unit_price;
            $goods->purchase_tax_kind = is_null($goods->purchase_tax_kind) ? TaxKindDefine::NORMAL : $goods->purchase_tax_kind;
            $goods->purchase_tax_type = is_null($goods->purchase_tax_type) ? TaxTypeDefine::EXCLUSIVE : $goods->purchase_tax_type;

            $url = \Storage::disk(config('app.goods_image_filesystem_driver'))->url('');
            $goods->image = str_replace($url, '', $goods->image);
        });
    }

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
                            $query->whereNull('sales_end_datetime')
                                ->orWhere('sales_end_datetime', '>=', date('Y-m-d H:i:s'));
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
        // メーカー割引適用（メーカー未選択の場合は割引対象とする）
        $isNotDiscount = isset($this->maker->is_not_discount) ? $this->maker->is_not_discount : false;
        if ($cartAttribute->getDiscountRate() && $isNotDiscount == false) {
            // 会員ランク割引あり かつ メーカー割引ありの場合
            // 割引額
            $discount = \Price::discount($salePrice, $cartAttribute->getDiscountRate(), $cartAttribute->getDiscountRoundingType());
            // 商品単価（税抜き） - 割引額
            $salePrice = (float)bcsub($salePrice, $discount, 2);
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

    /**
     * 消費税種類の名称を返す
     *
     * @return string 消費税種類の名称
     */
    public function getTaxKindValueAttribute()
    {
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


    // relation
    // 消費税モデルは常にロード
    protected $with = ['tax'];

    public function maker()
    {
        return $this->belongsTo(\App\Models\Maker::class);
    }

    public function tax()
    {
        return $this->hasTax(\App\Models\Tax::class);
    }

    public function goodsImages()
    {
        return $this->hasMany(\App\Models\GoodsImage::class)->orderBy('display_order', 'asc');
    }

    public function goodsStock()
    {
        return $this->hasMany(GoodsStock::class);
    }

    public function categories()
    {
        return $this->belongsToMany(
            \App\Models\Category::class,
            'goods_categories',
            'goods_id',
            'category_code',
            'id',
            'code'
        );
    }

    // cartItem interface
    /**
     * Get the unitprice of the Buyable item.
     *
     * @return float
     */
    public function getBuyableUnitPrice($options = null)
    {
        return $this->unit_price;
    }

    /**
     * 商品カテゴリを階層で取得する
     */
    public function categoryWithHierarchy()
    {
        $list = [];
        $firstCategory = $this->categories()->orderBy('path', 'desc')->first();
        if ($firstCategory == null) {
            return $list;
        }
        array_push($list, $firstCategory);
        // 自分の一つ上の親カテゴリを取得
        $parent = $firstCategory->getParent();
        while ($parent != null) {
            array_push($list, $parent);
            $parent = $parent->getParent();
        }
        return array_reverse($list);
    }

    /**
     * 商品一覧検索
     *
     * @param array $keyword
     * @param string $category
     * @param string $sort
     * @return array
     */
    public function getGoodslist($keywords, $category, $sort)
    {
        $goodslist = 
            goods::select([
                'goods.*'
                // ,DB::Raw('ifnull(categories.name,"") as category_name')
                ,DB::Raw('ifnull(makers.name,"") as maker_name')
                ,DB::Raw('ifnull(makers.name_kana,"") as maker_name_kana')
            ])
            ->from('goods')
            ->leftjoin('makers',function($join){
                $join->on('goods.maker_id', '=', 'makers.id')
                        ->where('makers.is_deleted','=','0');
            })
            ->where('goods.is_deleted','=','0')
            ->where('goods.is_published','=',StatusDefine::KOKAI_ON)
            ->where(function($query){
                $query->whereNull('goods.sales_start_datetime')
                        ->orwhere('goods.sales_start_datetime','<=',now());
            })
            ->where(function($query){
                $query->whereNull('goods.sales_end_datetime')
                        ->orwhere('goods.sales_end_datetime','>',now());
            });

        //商品一覧 キーワード検索
        $goodslist = $this->getGoodslistKeyword($goodslist, $keywords);
        //商品一覧 カテゴリ検索
        $goodslist = $this->getGoodslistCategory($goodslist, $category);
        //商品一覧 並び替え
        $goodslist = $this->getGoodslistSort($goodslist, $sort);

        //商品一覧取得
        $goodslist = $goodslist->paginate(Constants::GOODS_LIST_DISP_NUM);

        return $goodslist;
    }

    /**
     * 商品一覧 キーワード検索
     */
    public function getGoodslistKeyword($qurey, $keywords)
    {
        $goodslistkeyword = $qurey;
        if ($keywords) {
            foreach ($keywords as $k) {
                if ($k != "") {
                    $goodslistkeyword = $goodslistkeyword->whereRaw(
                        'CONCAT( 
                            ifnull(LCASE(goods.name),""),  
                            ifnull(LCASE(goods.code),""), 
                            ifnull(LCASE(goods.jan_code),""), 
                            ifnull(LCASE(makers.name),""), 
                            ifnull(LCASE(makers.name_kana),"")
                        ) LIKE CONCAT(\'%\', ?,\'%\')',
                        str_replace(array('\\', '%', '_'), array('\\\\', '\%', '\_'), $k)
                    );
                }
            }
        }

        return $goodslistkeyword;
    }

    /**
     * 商品一覧 カテゴリ検索
     */
    public function getGoodslistCategory($qurey, $category)
    {
        $categoryQuery = $qurey;
        $categorySub = 
            GoodsCategory::select('goods_id')
            ->join('categories', function($join) use ($category){
                $join->on('goods_categories.category_code', '=', 'categories.code');

                if (isset($category) && $category != '') {
                    $join->where('categories.path', 'like', '%'.$category.'~%');
                }
            })
            ->groupBy('goods_id');

        $categoryQuery->joinSub($categorySub, 'goods_categories', function($join){
            $join->on('goods.id', '=', 'goods_categories.goods_id');
        });

        return $categoryQuery;
    }

    /**
     * 商品一覧 並び替え
     */
    public function getGoodslistsort($qurey, $sort)
    {
        if($sort == 'name'){
            //商品名順
            $goodslistsort = $qurey->orderBy('name','asc');
        }elseif($sort == 'new'){
            //新着順
            $goodslistsort = $qurey->orderBy('updated_at','desc')
                                   ->orderBy('code','asc');
        }elseif($sort == 'maker'){
            //メーカー順
            $goodslistsort = $qurey->orderBy('maker_id','asc')
                                   ->orderBy('code','asc');
        }else{
            //未指定
            $goodslistsort = $qurey->orderBy('code','asc');
        }

        return $goodslistsort;
    }

    /**
     * 画像ファイルURL
     */
    public function getImageUrlAttribute()
    {
        return \Storage::disk(config('app.goods_image_filesystem_driver'))->url($this->image);
    }
}
