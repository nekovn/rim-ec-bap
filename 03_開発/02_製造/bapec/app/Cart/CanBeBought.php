<?php

namespace App\Cart;

trait CanBeBought
{
    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier($options = null)
    {
        return method_exists($this, 'getKey') ? $this->getKey() : $this->id;
    }

    /**
     * Get the name, title or description of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription($options = null)
    {
        if (($name = $this->getAttribute('name'))) {
            return $name;
        }

        if (($title = $this->getAttribute('title'))) {
            return $title;
        }

        if (($description = $this->getAttribute('description'))) {
            return $description;
        }
    }

    /**
     * Get the unitprice of the Buyable item.
     *
     * @return float
     */
    public function getBuyableUnitPrice($options = null)
    {
        if (($unitPrice = $this->getAttribute('unit_price'))) {
            return $unitPrice;
        }
    }

    /**
     * Get the weight of the Buyable item.
     *
     * @return float
     */
    public function getBuyableWeight($options = null)
    {
        if (($weight = $this->getAttribute('weight'))) {
            return $weight;
        }

        return 0;
    }

    /**
     * Get the image of the Buyable item.
     *
     * @return string
     */
    public function getBuyableImage($options = null)
    {
        if (($image = $this->getAttribute('image'))) {
            return $image;
        }
    }

    /**
     * Get the volume of the Buyable item.
     *
     * @return string
     */
    public function getBuyableVolume($options = null)
    {
        if (($volume = $this->getAttribute('volume'))) {
            return $volume;
        }
    }
}
