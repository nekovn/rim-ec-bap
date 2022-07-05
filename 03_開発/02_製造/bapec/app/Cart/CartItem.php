<?php

namespace App\Cart;

use App\Cart\Calculation\DefaultCalculator;
use App\Cart\Contracts\Buyable;
use App\Cart\Contracts\Calculator;
use App\Cart\Exceptions\InvalidCalculatorException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Arr;
use ReflectionClass;

/**
 * カート明細
 */
class CartItem implements Arrayable, Jsonable
{
    /**
     * The rowID of the cart item.
     *
     * @var string
     */
    public $rowId;

    /**
     * The ID of the cart item.
     *
     * @var int|string
     */
    public $id;

    /**
     * The quantity for this cart item.
     *
     * @var int|float
     */
    public $qty;

    /**
     * The name of the cart item.
     *
     * @var string
     */
    public $name;

    /**
     * The price without TAX of the cart item.
     *
     * @var float
     */
    public $unitPrice;

    /**
     * The weight of the product.
     *
     * @var float
     */
    public $weight;

    /**
     * The options for this cart item.
     *
     * @var array
     */
    public $options;

    /**
     * The tax rate for the cart item.
     *
     * @var int|float
     */
    public $taxRate = 0;

    /**
     * The FQN of the associated model.
     *
     * @var string|null
     */
    private $associatedModel = null;

    /**
     * The discount rate for the cart item.
     *
     * @var float
     */
    private $discountRate = 0;


    // Calculation result
    /**
     * 販売単価（税抜き）割引済み
     *
     * @var float
     */ 
    public $salePrice;

    /**
     * 販売単価（税抜き）に対する税
     *
     * @var float
     */ 
    public $salePriceTax;

    /**
     * 販売単価（税込）
     *
     * @var float
     */
    public $salePriceTaxIncluded;

    /**
     * 小計（税抜）
     *
     * @var float
     */
    public $subtotal;

    /**
     * 税
     *
     * @var float
     */
    public $tax;

    /**
     * 小計（税込）
     *
     * @var float
     */
    public $subtotalTaxIncluded;

    /**
     * 商品画像
     *
     * @var string
     */
    public $image;

    /**
     * 規格
     *
     * @var string
     */
    public $volume;

    /**
     * CartItem constructor.
     *
     * @param int|string $id
     * @param string     $name
     * @param float      $price
     * @param float      $weight
     * @param array      $options
     */
    public function __construct($id, $name, $price, $weight = 0, array $options = [])
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('Please supply a valid identifier.');
        }
        if (empty($name)) {
            throw new \InvalidArgumentException('Please supply a valid name.');
        }
        if (strlen($price) < 0 || !is_numeric($price)) {
            throw new \InvalidArgumentException('Please supply a valid price.');
        }
        if (strlen($weight) < 0 || !is_numeric($weight)) {
            throw new \InvalidArgumentException('Please supply a valid weight.');
        }

        $this->id = $id;
        $this->name = $name;
        $this->unitPrice = floatval($price);
        $this->weight = floatval($weight);
        $this->options = new CartItemOptions($options);
        $this->rowId = $this->generateRowId($id, $options);
    }

    /**
     * Returns the formatted weight.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function weight($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->weight, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * オリジナルの単価
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function unitPrice($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->unitPrice, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * 販売単価（税抜き）
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function salePrice($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->salePrice, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * 販売単価（税抜き）に対する税
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function salePriceTax($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->salePriceTax, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * 販売単価（税込み）
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function salePriceTaxIncluded($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->salePriceTaxIncluded, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * 小計（税抜）
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function subtotal($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->subtotal, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * 小計の税
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function tax($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->tax, $decimals, $decimalPoint, $thousandSeperator);
    }
            
    /**
     * 小計（税込）
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    public function subtotalTaxIncluded($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->numberFormat($this->subtotalTaxIncluded, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * 数量をセットする
     *
     * @param int|float $qty
     */
    public function setQuantity($qty)
    {
        if (empty($qty) || !is_numeric($qty)) {
            throw new \InvalidArgumentException('Please supply a valid quantity.');
        }

        $this->qty = $qty;
    }

    /**
     * Update the cart item from a Buyable.
     *
     * @param \App\Cart\Contracts\Buyable $item
     *
     * @return void
     */
    public function updateFromBuyable(Buyable $item)
    {
        $this->id = $item->getBuyableIdentifier($this->options);
        $this->name = $item->getBuyableDescription($this->options);
        $this->unitPrice = $item->getBuyableUnitPrice($this->options);
        $this->image = $item->getBuyableImage($this->options);
        $this->volume = $item->getBuyableVolume($this->options);
    }

    /**
     * Update the cart item from an array.
     *
     * @param array $attributes
     *
     * @return void
     */
    public function updateFromArray(array $attributes)
    {
        $this->id = Arr::get($attributes, 'id', $this->id);
        $this->qty = Arr::get($attributes, 'qty', $this->qty);
        $this->name = Arr::get($attributes, 'name', $this->name);
        $this->unitPrice = Arr::get($attributes, 'unit_price', $this->unitPrice);
        $this->weight = Arr::get($attributes, 'weight', $this->weight);
        $this->options = new CartItemOptions(Arr::get($attributes, 'options', $this->options));

        $this->image = Arr::get($attributes, 'image', $this->image);
        $this->volume = Arr::get($attributes, 'volume', $this->volume);

        $this->rowId = $this->generateRowId($this->id, $this->options->all());
    }

    /**
     * Associate the cart item with the given model.
     *
     * @param mixed $model
     *
     * @return \App\Cart\CartItem
     */
    public function associate($model)
    {
        $this->associatedModel = is_string($model) ? $model : get_class($model);

        return $this;
    }

    /**
     * Set the tax rate.
     *
     * @param int|float $taxRate
     *
     * @return \App\Cart\CartItem
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;

        return $this;
    }

    /**
     * Set the discount rate.
     *
     * @param int|float $discountRate
     *
     * @return \App\Cart\CartItem
     */
    public function setDiscountRate($discountRate)
    {
        $this->discountRate = $discountRate;

        return $this;
    }

    /**
     * Get an attribute from the cart item or get the associated model.
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function __get($attribute)
    {
        if (property_exists($this, $attribute)) {
            return $this->{$attribute};
        }
        $decimals = config('cart.format.decimals', 2);

        switch ($attribute) {
            case 'model':
                if (isset($this->associatedModel)) {
                    return with(new $this->associatedModel())->find($this->id);
                }
                // no break
            case 'modelFQCN':
                if (isset($this->associatedModel)) {
                    return $this->associatedModel;
                }
                // no break
            case 'weightTotal':
                return round($this->weight * $this->qty, $decimals);
        }

        $class = new ReflectionClass(config('cart.calculator', DefaultCalculator::class));
        if (!$class->implementsInterface(Calculator::class)) {
            throw new InvalidCalculatorException('The configured Calculator seems to be invalid. Calculators have to implement the Calculator Contract.');
        }

        return call_user_func($class->getName().'::getAttribute', $attribute, $this);
    }

    /**
     * Create a new instance from a Buyable.
     *
     * @param \App\Cart\Contracts\Buyable $item
     * @param array                                      $options
     *
     * @return \App\Cart\CartItem
     */
    public static function fromBuyable(Buyable $item, array $options = [])
    {
        $row = new self(
            $item->getBuyableIdentifier($options), 
            $item->getBuyableDescription($options), 
            $item->getBuyableUnitPrice($options), 
            $item->getBuyableWeight($options), 
            $options
        );

        $row->image = $item->getBuyableImage($options);
        $row->volume = $item->getBuyableVolume($options);

        return $row;
    }

    /**
     * Create a new instance from the given array.
     *
     * @param array $attributes
     *
     * @return \App\Cart\CartItem
     */
    public static function fromArray(array $attributes)
    {
        $options = Arr::get($attributes, 'options', []);

        $row = new self($attributes['id'], $attributes['name'], $attributes['unitPrice'], $attributes['weight'], $options);

        if (array_key_exists('image', $attributes)) {
            $row->image = $attributes['image'];
        }
        if (array_key_exists('volume', $attributes)) {
            $row->volume = $attributes['volume'];
        }

        return $row;
    }

    /**
     * Create a new instance from the given attributes.
     *
     * @param int|string $id
     * @param string     $name
     * @param float      $price
     * @param array      $options
     *
     * @return \App\Cart\CartItem
     */
    public static function fromAttributes($id, $name, $price, $weight, array $options = [])
    {
        return new self($id, $name, $price, $weight, $options);
    }

    /**
     * Generate a unique id for the cart item.
     *
     * @param string $id
     * @param array  $options
     *
     * @return string
     */
    protected function generateRowId($id, array $options)
    {
        ksort($options);

        return md5($id.serialize($options));
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'rowId'    => $this->rowId,
            'id'       => $this->id,
            'name'     => $this->name,
            'qty'      => $this->qty,
            'unitPrice'=> $this->unitPrice,
            'weight'   => $this->weight,
            'image'    => $this->image,
            'volume'   => $this->volume,
            'options'  => $this->options->toArray(),
            'discount' => $this->discount,
            'tax'      => $this->tax,
            'subtotal' => $this->subtotal,
            'salePriceTaxIncluded_v' => $this->salePriceTaxIncluded(),
            'subtotalTaxIncluded_v' => $this->subtotalTaxIncluded(),
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

    /**
     * Get the formatted number.
     *
     * @param float  $value
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     *
     * @return string
     */
    private function numberFormat($value, $decimals, $decimalPoint, $thousandSeperator)
    {
        if (is_null($decimals)) {
            $decimals = config('cart.format.decimals_output', 0);
        }

        if (is_null($decimalPoint)) {
            $decimalPoint = config('cart.format.decimal_point', '.');
        }

        if (is_null($thousandSeperator)) {
            $thousandSeperator = config('cart.format.thousand_separator', ',');
        }

        return number_format($value, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Getter for the raw internal discount rate.
     * Should be used in calculators.
     *
     * @return float
     */
    public function getDiscountRate()
    {
        return $this->discountRate;
    }
}
