<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\ShippingZonesDataInterface;

/**
 * Allows to get and set shipping zones values
 */
class ShippingZonesData extends AbstractExtensibleModel implements ShippingZonesDataInterface
{
    /**
     * @inheritdoc
     */
    public function getCountries()
    {
        return $this->getData(self::KEY_COUNTRIES);
    }

    /**
     * @inheritdoc
     */
    public function setCountries($countries)
    {
        return $this->setData(self::KEY_COUNTRIES, $countries);
    }

    /**
     * @inheritdoc
     */
    public function getRestOfWorld()
    {
        return $this->getData(self::KEY_WORLD);
    }

    /**
     * @inheritdoc
     */
    public function setRestOfWorld($worldFlag)
    {
        return $this->setData(self::KEY_WORLD, $worldFlag);
    }
}
