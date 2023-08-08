<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\TaxesDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class TaxesData extends AbstractExtensibleModel implements TaxesDataInterface
{
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
    public function getMeta()
    {
        return $this->getData(self::KEY_META);
    }

    /**
     * {@inheritdoc}
     */
    public function setMeta($meta)
    {
        return $this->setData(self::KEY_META, $meta);
    }
}
