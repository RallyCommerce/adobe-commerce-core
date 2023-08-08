<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Customer address interface.
 *
 * @api
 */
interface AddressInterface extends ExtensibleDataInterface
{
    public const ID = 'external_id';
    public const ADDRESS_LINE_1 = 'address1';
    public const ADDRESS_LINE_2 = 'address2';
    public const CITY = 'city';
    public const COMPANY = 'company';
    public const COUNTRY = 'country';
    public const COUNTRY_CODE = 'country_code';
    public const FIRSTNAME = 'firstname';
    public const LASTNAME = 'lastname';
    public const PHONE = 'phone';
    public const PROVINCE = 'province';
    public const ZIP = 'zip';

    /**
     * Get External ID
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Set External ID
     *
     * @param string $id
     * @return $this
     */
    public function setExternalId($id);

    /**
     * Get Address line 1
     *
     * @return string
     */
    public function getAddress1();

    /**
     * Set Address line 1
     *
     * @param string $address1
     * @return $this
     */
    public function setAddress1($address1);

    /**
     * Get Address line 2
     *
     * @return string
     */
    public function getAddress2();

    /**
     * Set Address line 2
     *
     * @param string $address2
     * @return $this
     */
    public function setAddress2($address2);

    /**
     * Get city name
     *
     * @return string
     */
    public function getCity();

    /**
     * Set city name
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany();

    /**
     * Set company
     *
     * @param string $company
     * @return $this
     */
    public function setCompany($company);

    /**
     * Get Country full name
     *
     * @return string
     */
    public function getCountry();

    /**
     * Set Country full name
     *
     * @param string $country
     * @return $this
     */
    public function setCountry($country);

    /**
     * Two-letter country code in ISO_3166-2 format
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
     * Get first name
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Set first name
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName);

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName();

    /**
     * Set last name
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName);

    /**
     * Get phone number
     *
     * @return string
     */
    public function getPhone();

    /**
     * Set phone number
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone);

    /**
     * Get Province
     *
     * @return string
     */
    public function getProvince();

    /**
     * Set Province
     *
     * @param string $province
     * @return $this
     */
    public function setProvince($province);

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip();

    /**
     * Set zip
     *
     * @param string $zip
     * @return $this
     */
    public function setZip($zip);
}
