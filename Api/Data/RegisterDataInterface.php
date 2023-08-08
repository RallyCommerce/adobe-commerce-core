<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface for register callback data
 *
 * @api
 */
interface RegisterDataInterface  extends ExtensibleDataInterface
{
    public const KEY_STORE_NAME = 'store_name';
    public const KEY_STORE_URL = 'store_url';
    public const KEY_STORE_LOGO = 'store_logo';

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
     * Get store logo
     *
     * @return string
     */
    public function getStoreLogo();

    /**
     * Set store logo
     *
     * @param string $storeLogo
     * @return $this
     */
    public function setStoreLogo($storeLogo);
}
