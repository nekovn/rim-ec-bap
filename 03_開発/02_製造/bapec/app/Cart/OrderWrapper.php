<?php

namespace App\Cart;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

/**
 * 注文データラッパークラス
 */
class OrderWrapper implements Arrayable, Jsonable
{
    /**
     * 注文ID
     *
     * @var int|string
     */
    private $id;

    /**
     * 注文データ
     *
     * @var \App\Models\Order
     */
    private $order;

    /**
     * 注文届け先データ
     *
     * @var \Illuminate\Support\Collection
     */
    private $orderDeliveries;

    /**
     * 注文明細データ
     *
     * @var \Illuminate\Support\Collection
     */
    private $orderDetails;

    /**
     * カート明細データ
     *
     * @var \Illuminate\Support\Collection
     */
    private $carts;

    /**
     * 
     */
    public function __construct()
    {
        $this->id = null;
        $this->order = new \StdClass();
        $this->orderDeliveries = new Collection([]);
        $this->orderDetails = new Collection([]);
        $this->carts = new Collection([]);
    }

    public function setId($id = null)
    {
        $this->id = $id;
    }

    public function setOrder($order = null)
    {
        $this->order = $order;
    }

    public function setOrderDeliveries($orderDeliveries = null)
    {
        $this->orderDeliveries = $orderDeliveries;
    }
    
    public function setOrderDetails($orderDetails = null)
    {
        $this->orderDetails = $orderDetails;
    }

    public function setCarts($carts = null)
    {
        $this->carts = $carts;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getOrderDeliveries()
    {
        return $this->orderDeliveries;
    }

    public function getOrderDetails()
    {
        return $this->orderDetails;
    }

    public function getCarts()
    {
        return $this->carts;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'       => $this->id,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
