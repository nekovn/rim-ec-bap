<?php

namespace App\Cart;

use Carbon\Carbon;
use App\Enums\RoundingTypeDefine;


/**
 * カート属性
 */
class CartAttribute
{
    /**
     * カート属性作成日
     *
     * @var Carbon
     */
    private $createdAt;

    /**
     * ユーザー別割引率
     *
     * @var float
     */
    private $discountRate = 0;

    /**
     * ユーザー別割引額
     *
     * @var float
     */
    private $discountPrice = 0;

    /**
     * 消費税端数処理区分
     *
     * @var string
     */
    private $taxRoundingType = RoundingTypeDefine::HALFUP;

    /**
     * 割引端数処理区分
     *
     * @var string
     */
    private $discountRoundingType = RoundingTypeDefine::HALFUP;


    public function setCreatedAt(Carbon $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setDiscountPrice(float $discountPrice)
    {
        $this->discountPrice = $discountPrice;
    }

    public function setDiscountRate(float $discountRate)
    {
        $this->discountRate = $discountRate;
    }

    public function setTaxRoundingType(String $taxRoundingType)
    {
        $this->taxRoundingType = $taxRoundingType;
    }

    public function setDiscountRoundingType(String $discountRoundingType)
    {
        $this->discountRoundingType = $discountRoundingType;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    public function getDiscountRate()
    {
        return $this->discountRate;
    }

    public function getTaxRoundingType()
    {
        return $this->taxRoundingType;
    }

    public function getDiscountRoundingType()
    {
        return $this->discountRoundingType;
    }
}
