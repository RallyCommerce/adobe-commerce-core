<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\OrderDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class OrderData extends AbstractExtensibleModel implements OrderDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getExternalId()
    {
        return $this->getData(self::KEY_EXTERNAL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::KEY_EXTERNAL_ID, $externalId);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalNumber()
    {
        return $this->getData(self::KEY_EXTERNAL_NUMBER);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalNumber($externalNumber)
    {
        return $this->setData(self::KEY_EXTERNAL_NUMBER, $externalNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->getData(self::KEY_STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        return $this->setData(self::KEY_STATUS, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtotal()
    {
        return $this->getData(self::KEY_SUBTOTAL);
    }

    /**
     * {@inheritdoc}
     */
    public function setSubtotal($subtotal)
    {
        return $this->setData(self::KEY_SUBTOTAL, $subtotal);
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal()
    {
        return $this->getData(self::KEY_TOTAL);
    }

    /**
     * {@inheritdoc}
     */
    public function setTotal($total)
    {
        return $this->setData(self::KEY_TOTAL, $total);
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxAmount()
    {
        return $this->getData(self::KEY_TAX_AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxAmount($taxAmount)
    {
        return $this->setData(self::KEY_TAX_AMOUNT, $taxAmount);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentCreated()
    {
        return $this->getData(self::KEY_PAYMENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPaymentCreated($payment)
    {
        return $this->setData(self::KEY_PAYMENT, $payment);
    }
}
