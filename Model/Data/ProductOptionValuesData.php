<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\ProductOptionValuesDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class ProductOptionValuesData extends AbstractExtensibleModel implements ProductOptionValuesDataInterface
{
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
    public function getPosition()
    {
        return $this->getData(self::KEY_POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        return $this->setData(self::KEY_POSITION, $position);
    }
}
