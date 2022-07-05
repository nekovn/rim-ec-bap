<?php

namespace App\Cart\Contracts;

interface Buyable
{
    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier($options = null);

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription($options = null);

    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyableUnitPrice($options = null);

    /**
     * Get the weight of the Buyable item.
     *
     * @return float
     */
    public function getBuyableWeight($options = null);
    
    /**
     * Get the image or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableImage($options = null);

    /**
     * Get the volume or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableVolume($options = null);
}
