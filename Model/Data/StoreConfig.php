<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * Allows to get and set store config values
 */
class StoreConfig extends AbstractExtensibleModel implements \Rally\Checkout\Api\Data\StoreConfigInterface
{
    /**
     * @inheritdoc
     */
    public function getOrganizationId()
    {
        return $this->getData(self::KEY_ORGANIZATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrganizationId($organizationId)
    {
        return $this->setData(self::KEY_ORGANIZATION_ID, $organizationId);
    }

    /**
     * @inheritdoc
     */
    public function getExternalId()
    {
        return $this->getData(self::KEY_EXTERNAL_ID);
    }

    /**
     * @inheritdoc
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::KEY_EXTERNAL_ID, $externalId);
    }

    /**
     * @inheritdoc
     */
    public function getCountryCode()
    {
        return $this->getData(self::KEY_COUNTRY_CODE);
    }

    /**
     * @inheritdoc
     */
    public function setCountryCode($countryCode)
    {
        return $this->setData(self::KEY_COUNTRY_CODE, $countryCode);
    }

    /**
     * @inheritdoc
     */
    public function getAddress()
    {
        return $this->getData(self::KEY_ADDRESS);
    }

    /**
     * @inheritdoc
     */
    public function setAddress($address)
    {
        return $this->setData(self::KEY_ADDRESS, $address);
    }

    /**
     * @inheritdoc
     */
    public function getStorePhone()
    {
        return $this->getData(self::KEY_PHONE);
    }

    /**
     * @inheritdoc
     */
    public function setStorePhone($storePhone)
    {
        return $this->setData(self::KEY_PHONE, $storePhone);
    }

    /**
     * @inheritdoc
     */
    public function getStoreLocale()
    {
        return $this->getData(self::KEY_LOCALE);
    }

    /**
     * @inheritdoc
     */
    public function setStoreLocale($storeLocale)
    {
        return $this->setData(self::KEY_LOCALE, $storeLocale);
    }

    /**
     * @inheritdoc
     */
    public function getTimezone()
    {
        return $this->getData(self::KEY_TIMEZONE);
    }

    /**
     * @inheritdoc
     */
    public function setTimezone($timezone)
    {
        return $this->setData(self::KEY_TIMEZONE, $timezone);
    }

    /**
     * @inheritdoc
     */
    public function getCurrency()
    {
        return $this->getData(self::KEY_CURRENCY);
    }

    /**
     * @inheritdoc
     */
    public function setCurrency($currency)
    {
        return $this->setData(self::KEY_CURRENCY, $currency);
    }

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
    public function getNativeCheckoutUrl()
    {
        return $this->getData(self::KEY_NATIVE_CHECKOUT_URL);
    }

    /**
     * @inheritdoc
     */
    public function setNativeCheckoutUrl($checkoutUrl)
    {
        return $this->setData(self::KEY_NATIVE_CHECKOUT_URL, $checkoutUrl);
    }

    /**
     * @inheritdoc
     */
    public function getCartUrl()
    {
        return $this->getData(self::KEY_CART_URL);
    }

    /**
     * @inheritdoc
     */
    public function setCartUrl($cartUrl)
    {
        return $this->setData(self::KEY_CART_URL, $cartUrl);
    }

    /**
     * @inheritdoc
     */
    public function getLogoUrl()
    {
        return $this->getData(self::KEY_LOGO_URL);
    }

    /**
     * @inheritdoc
     */
    public function setLogoUrl($logoUrl)
    {
        return $this->setData(self::KEY_LOGO_URL, $logoUrl);
    }

    /**
     * @inheritdoc
     */
    public function getMeta()
    {
        return $this->getData(self::KEY_META);
    }

    /**
     * @inheritdoc
     */
    public function setMeta($meta)
    {
        return $this->setData(self::KEY_META, $meta);
    }
}
