<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\DiscountsInterface;

/**
 * @codeCoverageIgnoreStart
 */
class Discounts extends AbstractExtensibleModel implements DiscountsInterface
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
    public function getType()
    {
        return $this->getData(self::KEY_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        return $this->setData(self::KEY_TYPE, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtype()
    {
        return $this->getData(self::KEY_SUBTYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setSubtype($subtype)
    {
        return $this->setData(self::KEY_SUBTYPE, $subtype);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getData(self::KEY_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        return $this->setData(self::KEY_NAME, $name);
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
