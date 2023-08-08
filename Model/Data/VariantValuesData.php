<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\VariantValuesDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class VariantValuesData extends AbstractExtensibleModel implements VariantValuesDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getProductOptionValueId()
    {
        return $this->getData(self::KEY_VALUE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductOptionValueId($valueId)
    {
        return $this->setData(self::KEY_VALUE_ID, $valueId);
    }
}
