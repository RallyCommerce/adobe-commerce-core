<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\RegisterDataInterface;

/**
 * Allows to get and set store config values
 */
class RegisterData extends AbstractExtensibleModel implements RegisterDataInterface
{
    /**
     * @inheritdoc
     */
    public function getStoreName()
    {
        return $this->getData(self::KEY_STORE_NAME);
    }

    /**
     * @inheritdoc
     */
    public function setStoreName($storeName)
    {
        return $this->setData(self::KEY_STORE_NAME, $storeName);
    }

    /**
     * @inheritdoc
     */
    public function getStoreUrl()
    {
        return $this->getData(self::KEY_STORE_URL);
    }

    /**
     * @inheritdoc
     */
    public function setStoreUrl($storeUrl)
    {
        return $this->setData(self::KEY_STORE_URL, $storeUrl);
    }

    /**
     * @inheritdoc
     */
    public function getStoreLogo()
    {
        return $this->getData(self::KEY_STORE_LOGO);
    }

    /**
     * @inheritdoc
     */
    public function setStoreLogo($storeLogo)
    {
        return $this->setData(self::KEY_STORE_LOGO, $storeLogo);
    }
}
