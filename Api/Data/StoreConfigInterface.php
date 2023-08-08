<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\DataObject;

/**
 * Interface for store config
 *
 * @api
 */
interface StoreConfigInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_ORGANIZATION_ID = 'organization_id';
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_COUNTRY_CODE = 'country_code';
    public const KEY_ADDRESS = 'address';
    public const KEY_PHONE = 'store_phone';
    public const KEY_LOCALE = 'store_locale';
    public const KEY_TIMEZONE = 'timezone';
    public const KEY_CURRENCY = 'currency';
    public const KEY_STORE_NAME = 'store_name';
    public const KEY_STORE_URL = 'store_url';
    public const KEY_NATIVE_CHECKOUT_URL = 'native_checkout_url';
    public const KEY_CART_URL = 'cart_url';
    public const KEY_LOGO_URL = 'logo_url';
    public const KEY_META = 'meta';

    /**
     * Get organization id
     *
     * @return string
     */
    public function getOrganizationId();

    /**
     * Set organization id
     *
     * @param string $organizationId
     * @return $this
     */
    public function setOrganizationId($organizationId);

    /**
     * Get External ID
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Set External ID
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId);

    /**
     * Get country code of the store
     *
     * @return string
     */
    public function getCountryCode();

    /**
     * Set country code
     *
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode($countryCode);

    /**
     * Get store address
     *
     * @return string
     */
    public function getAddress();

    /**
     * Set store address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address);

    /**
     * Get store phone
     *
     * @return string
     */
    public function getStorePhone();

    /**
     * Set store phone
     *
     * @param string $storePhone
     * @return $this
     */
    public function setStorePhone($storePhone);

    /**
     * Get store locale
     *
     * @return string
     */
    public function getStoreLocale();

    /**
     * Set store locale
     *
     * @param string $storeLocale
     * @return $this
     */
    public function setStoreLocale($storeLocale);

    /**
     * Get timezone of the store
     *
     * @return string
     */
    public function getTimezone();

    /**
     * Set timezone of the store
     *
     * @param string $timezone
     * @return $this
     */
    public function setTimezone($timezone);

    /**
     * Return store currency
     *
     * @return string
     */
    public function getCurrency();

    /**
     * Set store currency
     *
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency);

    /**
     * Get store name
     *
     * @return string
     */
    public function getStoreName();

    /**
     * Set store name
     *
     * @param string $storeName
     * @return $this
     */
    public function setStoreName($storeName);

    /**
     * Get store URL
     *
     * @return string
     */
    public function getStoreUrl();

    /**
     * Set store URL
     *
     * @param string $storeUrl
     * @return $this
     */
    public function setStoreUrl($storeUrl);

    /**
     * Get native checkout URL
     *
     * @return string
     */
    public function getNativeCheckoutUrl();

    /**
     * Set native checkout URL
     *
     * @param string $checkoutUrl
     * @return $this
     */
    public function setNativeCheckoutUrl($checkoutUrl);

    /**
     * Get cart URL
     *
     * @return string
     */
    public function getCartUrl();

    /**
     * Set cart URL
     *
     * @param string $cartUrl
     * @return $this
     */
    public function setCartUrl($cartUrl);

    /**
     * Get logo URL
     *
     * @return string
     */
    public function getLogoUrl();

    /**
     * Set logo URL
     *
     * @param string $logoUrl
     * @return $this
     */
    public function setLogoUrl($logoUrl);

    /**
     * Get Meta
     *
     * @return string
     */
    public function getMeta();

    /**
     * Set Meta
     *
     * @param string $meta
     * @return $this
     */
    public function setMeta($meta);
}
