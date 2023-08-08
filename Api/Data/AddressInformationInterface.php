<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface AddressInformationInterface
 *
 * @api
 */
interface AddressInformationInterface extends ExtensibleDataInterface
{
    public const BILLING_ADDRESS = 'billing_address';
    public const SHIPPING_ADDRESS = 'shipping_address';
    public const EMAIL = 'email';
    public const EXTERNAL_ID = 'external_id';
    public const FIRSTNAME = 'first_name';
    public const LASTNAME = 'last_name';
    public const SAME_AS_BILLING = 'same_as_billing';
    public const PHONE = 'phone';
    public const CONSENT = 'consent';

    /**
     * Returns billing address
     *
     * @return \Rally\Checkout\Api\Data\AddressInterface|null
     */
    public function getBillingAddress();

    /**
     * Set billing address if additional synchronization needed
     *
     * @param \Rally\Checkout\Api\Data\AddressInterface $address
     * @return $this
     */
    public function setBillingAddress(\Rally\Checkout\Api\Data\AddressInterface $address);

    /**
     * Returns shipping address
     *
     * @return \Rally\Checkout\Api\Data\AddressInterface
     */
    public function getShippingAddress();

    /**
     * Set shipping address
     *
     * @param \Rally\Checkout\Api\Data\AddressInterface $address
     * @return $this
     */
    public function setShippingAddress(\Rally\Checkout\Api\Data\AddressInterface $address);

    /**
     * Returns customer email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Set customer email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * Returns external ID
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Set external ID
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId);

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
     * Is address same as billing
     *
     * @return bool
     */
    public function getIsBillingEqualShipping();

    /**
     * Set address same as billing
     *
     * @param bool $sameAsBilling
     * @return $this
     */
    public function setIsBillingEqualShipping($sameAsBilling);

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
     * Get consent
     *
     * @return \Rally\Checkout\Api\Data\ConsentInterface
     */
    public function getConsent();

    /**
     * Set consent
     *
     * @param \Rally\Checkout\Api\Data\ConsentInterface $consent
     * @return $this
     */
    public function setConsent(\Rally\Checkout\Api\Data\ConsentInterface $consent);
}
