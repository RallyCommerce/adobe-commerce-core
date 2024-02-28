<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface for order data
 *
 * @api
 */
interface OrderDataInterface  extends ExtensibleDataInterface
{
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_EXTERNAL_NUMBER = 'external_number';
    public const KEY_CART_ID = 'cart_id';
    public const KEY_STATUS = 'status';
    public const KEY_SUBTOTAL = 'subtotal';
    public const KEY_TOTAL = 'total';
    public const KEY_TAX_AMOUNT = 'tax_amount';
    public const KEY_DISCOUNTS = 'discounts';
    public const KEY_TAXES = 'taxes';
    public const KEY_SHIPPING_LINES = 'shipping_lines';
    public const KEY_HANDLING_FEE = 'handling_fee';
    public const KEY_SHIPPING_COST = 'shipping_cost';
    public const KEY_LINE_ITEMS = 'line_items';
    public const KEY_META = 'meta';
    public const KEY_PAYMENT = 'payment_created';

    /**
     * Get External ID
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Set External ID
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId);

    /**
     * Get External Number
     *
     * @return string
     */
    public function getExternalNumber();

    /**
     * Set External Number
     *
     * @param string $externalNumber
     * @return $this
     */
    public function setExternalNumber($externalNumber);

    /**
     * Get cart ID
     *
     * @return string
     */
    public function getCartId();

    /**
     * Set cart ID
     *
     * @param string $cartId
     * @return $this
     */
    public function setCartId($cartId);

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get Subtotal
     *
     * @return float
     */
    public function getSubtotal();

    /**
     * Set Subtotal
     *
     * @param float $subtotal
     * @return $this
     */
    public function setSubtotal($subtotal);

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal();

    /**
     * Set total
     *
     * @param float $total
     * @return $this
     */
    public function setTotal($total);

    /**
     * Get tax amount
     *
     * @return float
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
     * Get Discounts Data
     *
     * @return \Rally\Checkout\Api\Data\DiscountsInterface[]
     */
    public function getDiscounts();

    /**
     * Set Discounts Data
     *
     * @param array $discounts
     * @return $this
     */
    public function setDiscounts($discounts);

    /**
     * Get taxes
     *
     * @return \Rally\Checkout\Api\Data\TaxesDataInterface[]
     */
    public function getTaxes();

    /**
     * Set taxes
     *
     * @param array $taxes
     * @return $this
     */
    public function setTaxes($taxes);

    /**
     * Get shipping line
     *
     * @return \Rally\Checkout\Api\Data\ShippingLinesDataInterface[]
     */
    public function getShippingLines();

    /**
     * Set shipping line
     *
     * @param array $shippingLines
     * @return $this
     */
    public function setShippingLines($shippingLines);

    /**
     * Get handling fee
     *
     * @return \Rally\Checkout\Api\Data\ShippingLinesDataInterface
     */
    public function getHandlingFee();

    /**
     * Set handling fee
     *
     * @param \Rally\Checkout\Api\Data\ShippingLinesDataInterface $handlingFee
     * @return $this
     */
    public function setHandlingFee($handlingFee);

    /**
     * Get shipping cost
     *
     * @return \Rally\Checkout\Api\Data\ShippingLinesDataInterface
     */
    public function getShippingCost();

    /**
     * Set shipping cost
     *
     * @param \Rally\Checkout\Api\Data\ShippingLinesDataInterface $shippingCost
     * @return $this
     */
    public function setShippingCost($shippingCost);

    /**
     * Get line items
     *
     * @return \Rally\Checkout\Api\Data\LineItemsInterface[]
     */
    public function getLineItems();

    /**
     * Set line items
     *
     * @param array $lineItems
     * @return $this
     */
    public function setLineItems($lineItems);

    /**
     * Get Meta
     *
     * @return string
     */
    public function getMeta();

    /**
     * Set Meta
     *
     * @param string $meta
     * @return $this
     */
    public function setMeta($meta);

    /**
     * Get payment data
     *
     * @return \Rally\Checkout\Api\Data\PaymentDataInterface
     */
    public function getPaymentCreated();

    /**
     * Set payment data
     *
     * @param \Rally\Checkout\Api\Data\PaymentDataInterface $payment
     * @return $this
     */
    public function setPaymentCreated($payment);
}
