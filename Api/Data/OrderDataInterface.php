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
    public const KEY_STATUS = 'status';
    public const KEY_SUBTOTAL = 'subtotal';
    public const KEY_TOTAL = 'total';
    public const KEY_TAX_AMOUNT = 'tax_amount';
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
