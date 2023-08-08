<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface TaxesDataInterface
 * @api
 */
interface TaxesDataInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_TITLE = 'title';
    public const KEY_TAX_RATE = 'tax_rate';
    public const KEY_TAX_AMOUNT = 'tax_amount';
    public const KEY_META = 'meta';

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get tax rate
     *
     * @return float|null
     */
    public function getTaxRate();

    /**
     * Set tax rate
     *
     * @param float $taxRate
     * @return $this
     */
    public function setTaxRate($taxRate);

    /**
     * Get tax amount
     *
     * @return float|null
     */
    public function getTaxAmount();

    /**
     * Set tax amount
     *
     * @param float $taxAmount
     * @return $this
     */
    public function setTaxAmount($taxAmount);

    /**
     * Get meta
     *
     * @return string|null
     */
    public function getMeta();

    /**
     * Set meta
     *
     * @param string $meta
     * @return $this
     */
    public function setMeta($meta);
}
