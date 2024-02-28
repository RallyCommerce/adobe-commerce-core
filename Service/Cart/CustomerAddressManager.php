<?php

namespace Rally\Checkout\Service\Cart;

use Magento\Newsletter\Model\Subscriber;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote\Address;
use Magento\Sales\Api\Data\OrderInterface;
use Rally\Checkout\Api\Data\AddressInterface;
use Rally\Checkout\Api\Data\ConsentInterfaceFactory;
use Rally\Checkout\Api\Data\AddressInterfaceFactory;
use Rally\Checkout\Api\Data\AddressInformationInterface;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Rally\Checkout\Api\Data\AddressInformationInterfaceFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;

class CustomerAddressManager
{
    public function __construct(
        protected SubscriberFactory $subscriberFactory,
        protected ConsentInterfaceFactory $consentFactory,
        protected AddressInterfaceFactory $addressFactory,
        protected AddressInformationInterfaceFactory $addressInfoFactory,
        protected CountryInformationAcquirerInterface $countryInformation,
        protected SubscriptionManagerInterface $subscriptionManager
    ) {
    }

    /**
     * Get customer cart data
     *
     * @param CartInterface $quote
     * @return AddressInformationInterface
     * @throws NoSuchEntityException
     */
    public function getCartData(CartInterface $quote): AddressInformationInterface
    {
        $customerData = $this->addressInfoFactory->create();
        $consentData = $this->consentFactory->create();
        $shippingAddress = $quote->getShippingAddress();
        $billingAddress = $quote->getBillingAddress();
        $email = $quote->getCustomerEmail() ?? $billingAddress->getEmail();

        if ($email) {
            $websiteId = $quote->getStore()->getWebsiteId();
            $subscriber = $this->getSubscriptionByEmail($email, $websiteId);
            $consentData->setEmail($subscriber->isSubscribed());

            if (!$quote->getCustomerEmail()) {
                $quote->setCustomerEmail($email);
            }
        }

        $billingAddressData = $this->getAddressData($billingAddress);
        $shippingAddressData = $this->getAddressData($shippingAddress);

        $customerData->setBillingAddress($billingAddressData)
            ->setShippingAddress($shippingAddressData)
            ->setEmail($email)
            ->setExternalId($quote->getCustomerId() ?? 'guest-' . $quote->getId())
            ->setFirstName($quote->getCustomerFirstname())
            ->setLastName($quote->getCustomerLastname())
            ->setIsBillingEqualShipping($quote->getShippingAddress()->getSameAsBilling())
            ->setPhone($quote->getShippingAddress()->getTelephone())
            ->setConsent($consentData);

        return $customerData;
    }

    /**
     * Get customer address data
     *
     * @param QuoteAddressInterface|Address|null $quoteAddress
     * @return AddressInterface
     * @throws NoSuchEntityException
     */
    private function getAddressData(QuoteAddressInterface|Address|null $quoteAddress): AddressInterface
    {
        $addressData = $this->addressFactory->create();
        $street = $quoteAddress->getStreet();
        $region = is_object($quoteAddress->getRegion()) ? $quoteAddress->getRegion() : $quoteAddress;

        try {
            $countryName = $quoteAddress->getCountryId() ?
                $this->countryInformation->getCountryInfo($quoteAddress->getCountryId())->getFullNameEnglish() : null;
        } catch (NoSuchEntityException $e) {
            $countryName = null;
            $quoteAddress->getQuote()->setHasCountryError(true);
        }

        $addressData->setExternalId($quoteAddress->getId())
            ->setAddress1($street[0] ?: null)
            ->setAddress2($street[1] ?? null)
            ->setCity($quoteAddress->getCity())
            ->setCompany($quoteAddress->getCompany())
            ->setCountry($countryName)
            ->setCountryCode($quoteAddress->getCountryId())
            ->setFirstName($quoteAddress->getFirstName())
            ->setLastName($quoteAddress->getLastName())
            ->setPhone($quoteAddress->getTelephone())
            ->setProvince($region->getRegion())
            ->setZip($quoteAddress->getPostcode());

        return $addressData;
    }

    /**
     * Update customer cart data
     *
     * @param CartInterface $quote
     * @param array $rallyCartData
     * @return void
     * @throws LocalizedException
     */
    public function updateCartData(CartInterface $quote, array $rallyCartData): void
    {
        $rallyCustomerData = $rallyCartData['customer'];
        $email = $rallyCustomerData['email'] ?? $quote->getCustomerEmail();

        if (isset($rallyCustomerData['consent']['email']) && $email) {
            $websiteId = $quote->getStore()->getWebsiteId();
            $rallyStatus = (bool) $rallyCustomerData['consent']['email'];
            $subscriber = $this->getSubscriptionByEmail($email, $websiteId);
            $status = $subscriber->isSubscribed();

            if ($rallyStatus != $status && $rallyStatus) {
                $this->subscriptionManager->subscribe($email, $quote->getStoreId());
            } elseif ($rallyStatus != $status) {
                $subscriber->unsubscribe();
            }
        }
    }

    /**
     * Get order data
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderData(OrderInterface $order): array
    {
        $email = $order->getCustomerEmail();
        $consentData = ["email" => false];
        if ($email) {
            $websiteId = $order->getStore()->getWebsiteId();
            $subscriber = $this->getSubscriptionByEmail($email, $websiteId);
            $consentData["email"] = $subscriber->isSubscribed();
        }

        return [
            "external_id" => $order->getCustomerId() ?? 'guest-' . $order->getQuoteId(),
            "first_name" => $order->getCustomerFirstname(),
            "last_name" => $order->getCustomerLastname(),
            "email" => $order->getCustomerEmail(),
            "phone" => $order->getBillingAddress()->getTelephone(),
            "consent" => $consentData
        ];
    }

    /**
     * Get subscription by email
     *
     * @param string $email
     * @param int $websiteId
     * @return Subscriber
     */
    private function getSubscriptionByEmail(string $email, int $websiteId): Subscriber
    {
        return $this->subscriberFactory->create()->loadBySubscriberEmail($email, $websiteId);
    }
}
