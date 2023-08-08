<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface PaymentDataInterface
 * @api
 */
interface PaymentDataInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_TRANSACTION_ID = 'external_platform_transaction_id';
    public const KEY_AMOUNT = 'amount';

    /**
     * Get Transaction ID
     *
     * @return string|null
     */
    public function getExternalPlatformTransactionId();

    /**
     * Set Transaction ID
     *
     * @param string $tranId
     * @return $this
     */
    public function setExternalPlatformTransactionId($tranId);

    /**
     * Get amount
     *
     * @return float|null
     */
    public function getAmount();

    /**
     * Set amount
     *
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount);
}
