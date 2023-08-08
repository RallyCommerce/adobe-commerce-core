<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\AddressInterface;

/**
 * Class Address Data Model implementing the Address interface
 *
 * @api
 */
class Address extends AbstractExtensibleModel implements AddressInterface
{
    /**
     * {@inheritdoc}
     */
    public function getExternalId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress1()
    {
        return $this->getData(self::ADDRESS_LINE_1);
    }

    /**
     * {@inheritdoc}
     */
    public function setAddress1($address1)
    {
        return $this->setData(self::ADDRESS_LINE_1, $address1);
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress2()
    {
        return $this->getData(self::ADDRESS_LINE_2);
    }

    /**
     * {@inheritdoc}
     */
    public function setAddress2($address2)
    {
        return $this->setData(self::ADDRESS_LINE_2, $address2);
    }

    /**
     * {@inheritdoc}
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * {@inheritdoc}
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * {@inheritdoc}
     */
    public function getCompany()
    {
        return $this->getData(self::COMPANY);
    }

    /**
     * {@inheritdoc}
     */
    public function setCompany($company)
    {
        return $this->setData(self::COMPANY, $company);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry()
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * {@inheritdoc}
     */
    public function setCountry($country)
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryCode()
    {
        return $this->getData(self::COUNTRY_CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryCode($countryCode)
    {
        return $this->setData(self::COUNTRY_CODE, $countryCode);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName()
    {
        return $this->getData(self::FIRSTNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstName($firstName)
    {
        return $this->setData(self::FIRSTNAME, $firstName);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName()
    {
        return $this->getData(self::LASTNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setLastName($lastName)
    {
        return $this->setData(self::LASTNAME, $lastName);
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone()
    {
        return $this->getData(self::PHONE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPhone($phone)
    {
        return $this->setData(self::PHONE, $phone);
    }

    /**
     * {@inheritdoc}
     */
    public function getProvince()
    {
        return $this->getData(self::PROVINCE);
    }

    /**
     * {@inheritdoc}
     */
    public function setProvince($province)
    {
        return $this->setData(self::PROVINCE, $province);
    }

    /**
     * {@inheritdoc}
     */
    public function getZip()
    {
        return $this->getData(self::ZIP);
    }

    /**
     * {@inheritdoc}
     */
    public function setZip($zip)
    {
        return $this->setData(self::ZIP, $zip);
    }
}
