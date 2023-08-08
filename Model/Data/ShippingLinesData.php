<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\ShippingLinesDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class ShippingLinesData extends AbstractExtensibleModel implements ShippingLinesDataInterface
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
    public function setExternalId($id)
    {
        return $this->setData(self::KEY_EXTERNAL_ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getData(self::KEY_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        return $this->setData(self::KEY_TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getCarrierIdentifier()
    {
        return $this->getData(self::KEY_CARRIER_IDENTIFIER);
    }

    /**
     * {@inheritdoc}
     */
    public function setCarrierIdentifier($carrier)
    {
        return $this->setData(self::KEY_CARRIER_IDENTIFIER, $carrier);
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->getData(self::KEY_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        return $this->setData(self::KEY_CODE, $code);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->getData(self::KEY_DESCRIPTION);
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        return $this->setData(self::KEY_DESCRIPTION, $description);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice()
    {
        return $this->getData(self::KEY_PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice($price)
    {
        return $this->setData(self::KEY_PRICE, $price);
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
    public function getTaxRate()
    {
        return $this->getData(self::KEY_TAX_RATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxRate($taxRate)
    {
        return $this->setData(self::KEY_TAX_RATE, $taxRate);
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
}
