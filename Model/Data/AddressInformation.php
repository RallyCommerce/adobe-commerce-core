<?php

namespace Rally\Checkout\Model\Data;

use Rally\Checkout\Api\Data\AddressInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\AddressInformationInterface;

/**
 * @codeCoverageIgnoreStart
 */
class AddressInformation extends AbstractExtensibleModel implements AddressInformationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBillingAddress()
    {
        return $this->getData(self::BILLING_ADDRESS);
    }

    /**
     * {@inheritdoc}
     */
    public function setBillingAddress(AddressInterface $address)
    {
        return $this->setData(self::BILLING_ADDRESS, $address);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddress()
    {
        return $this->getData(self::SHIPPING_ADDRESS);
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAddress(AddressInterface $address)
    {
        return $this->setData(self::SHIPPING_ADDRESS, $address);
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalId()
    {
        return $this->getData(self::EXTERNAL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::EXTERNAL_ID, $externalId);
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
    public function getIsBillingEqualShipping()
    {
        return $this->getData(self::SAME_AS_BILLING);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsBillingEqualShipping($sameAsBilling)
    {
        return $this->setData(self::SAME_AS_BILLING, $sameAsBilling);
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
    public function getConsent()
    {
        return $this->getData(self::CONSENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setConsent($consent)
    {
        return $this->setData(self::CONSENT, $consent);
    }
}
