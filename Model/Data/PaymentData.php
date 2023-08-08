<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\PaymentDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class PaymentData extends AbstractExtensibleModel implements PaymentDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getExternalPlatformTransactionId()
    {
        return $this->getData(self::KEY_TRANSACTION_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalPlatformTransactionId($tranId)
    {
        return $this->setData(self::KEY_TRANSACTION_ID, $tranId);
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount()
    {
        return $this->getData(self::KEY_AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount($amount)
    {
        return $this->setData(self::KEY_AMOUNT, $amount);
    }
}
