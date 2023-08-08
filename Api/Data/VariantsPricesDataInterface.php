<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface VariantsPricesDataInterface
 * @api
 */
interface VariantsPricesDataInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_CURRENCY = 'currency';
    public const KEY_PRICE = 'price';
    public const KEY_DISCOUNTED_PRICE = 'discounted_price';

    /**
     * Get currency
     *
     * @return string|null
     */
    public function getCurrency();

    /**
     * Set currency
     *
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency);

    /**
     * Get price
     *
     * @return float|null
     */
    public function getPrice();

    /**
     * Set price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * Get discounted price
     *
     * @return float|null
     */
    public function getDiscountedPrice();

    /**
     * Set discounted price
     *
     * @param float $discountedPrice
     * @return $this
     */
    public function setDiscountedPrice($discountedPrice);
}
