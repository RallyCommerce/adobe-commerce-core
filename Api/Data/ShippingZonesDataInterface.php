<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface for shipping zones data
 *
 * @api
 */
interface ShippingZonesDataInterface extends ExtensibleDataInterface
{
    public const KEY_COUNTRIES = 'countries';
    public const KEY_WORLD = 'rest_of_world';

    /**
     * Get countries
     *
     * @return \Magento\Framework\DataObject
     */
    public function getCountries();

    /**
     * Set countries
     *
     * @param \Magento\Framework\DataObject $countries
     * @return $this
     */
    public function setCountries($countries);

    /**
     * Get World flag
     *
     * @return bool
     */
    public function getRestOfWorld();

    /**
     * Set World flag
     *
     * @param bool $worldFlag
     * @return $this
     */
    public function setRestOfWorld($worldFlag);
}
