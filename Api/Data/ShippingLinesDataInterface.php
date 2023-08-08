<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ShippingLinesDataInterface
 *
 * @api
 */
interface ShippingLinesDataInterface extends ExtensibleDataInterface
{
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_TITLE = 'title';
    public const KEY_CARRIER_IDENTIFIER = 'carrier_identifier';
    public const KEY_CODE = 'code';
    public const KEY_DESCRIPTION = 'description';
    public const KEY_PRICE = 'price';
    public const KEY_SUBTOTAL = 'subtotal';
    public const KEY_TAX_RATE = 'tax_rate';
    public const KEY_TAX_AMOUNT = 'tax_amount';
    public const KEY_TOTAL = 'total';

    /**
     * Get External ID
     *
     * @return string|null
     */
    public function getExternalId();

    /**
     * Set External ID
     *
     * @param string $id
     * @return $this
     */
    public function setExternalId($id);

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
     * Get carrier identifier
     *
     * @return string|null
     */
    public function getCarrierIdentifier();

    /**
     * Set carrier identifier
     *
     * @param string $carrier
     * @return $this
     */
    public function setCarrierIdentifier($carrier);

    /**
     * Get code
     *
     * @return string|null
     */
    public function getCode();

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

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
     * Get subtotal
     *
     * @return float|null
     */
    public function getSubtotal();

    /**
     * Set subtotal
     *
     * @param float $subtotal
     * @return $this
     */
    public function setSubtotal($subtotal);

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
     * Get total
     *
     * @return float|null
     */
    public function getTotal();

    /**
     * Set total
     *
     * @param float $total
     * @return $this
     */
    public function setTotal($total);
}
