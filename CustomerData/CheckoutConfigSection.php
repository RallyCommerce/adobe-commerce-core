<?php

namespace Rally\Checkout\CustomerData;

use Magento\Quote\Model\Quote;
use Rally\Checkout\Api\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;

class CheckoutConfigSection implements SectionSourceInterface
{
    public function __construct(
        private readonly ConfigInterface $rallyConfig,
        private readonly CheckoutSession $checkoutSession,
        private readonly AccountManagementInterface $accountManagement,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CountryInformationAcquirerInterface $countryInformation,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getSectionData(): array
    {
        return $this->getCheckoutConfig();
    }

    /**
     * Retrieve checkout configuration
     *
     * @return array
     */
    private function getCheckoutConfig(): array
    {
        $checkoutData = [];
        $quote = $this->getQuote();
        $isEnable = $this->rallyConfig->isEnabled();

        if ($isEnable && $quote && $quote->getId() && $quote->getItemsCount()) {
            $checkoutData = [
                "id" => $this->rallyConfig->getFormattedId("quote", $quote->getId(), $quote->getCreatedAt()),
                "currency" => $quote->getQuoteCurrencyCode(),
                "customerData" => $this->getCustomerData($quote)
            ];
        }
        $checkoutData["rallyConfig"] = $this->getConfigData();
        return $checkoutData;
    }

    /**
     * Retrieve customer quote from session
     *
     * @return Quote|null
     */
    private function getQuote(): ?Quote
    {
        try {
            return $this->checkoutSession->getQuote();
        } catch (NoSuchEntityException|LocalizedException $e) {
            return null;
        }
    }

    /**
     * Retrieve customer data
     *
     * @param Quote $quote
     * @return array
     */
    private function getCustomerData(Quote $quote): array
    {
        $customerData = [];
        $isGuest = $quote->getCustomerIsGuest();

        if (!$isGuest) {
            try {
                $customer = $this->customerRepository->getById($quote->getCustomerId());
                $customerData = [
                    "email" => $customer->getEmail(),
                    "firstName" => $customer->getFirstName(),
                    "lastName" => $customer->getLastName()
                ];
            } catch (NoSuchEntityException|LocalizedException $e) {
            }
        }

        if (empty($customerData)) {
            $customerData = [
                "email" => $quote->getCustomerEmail(),
                "firstName" => $quote->getCustomerFirstName(),
                "lastName" => $quote->getCustomerLastName()
            ];
        }

        $billingAddress = $this->getDefaultAddress($quote, 'billing');
        $shippingAddress = $this->getDefaultAddress($quote, 'shipping');
        $customerData['phone'] = $billingAddress->getTelephone();
        $customerData['shipping'] = $this->getCustomerAddress($shippingAddress);
        $customerData['billing'] = $this->getCustomerAddress($billingAddress);

        return $customerData;
    }

    /**
     * Get customer default address
     *
     * @param Quote $quote
     * @param string $type
     * @return AddressInterface|QuoteAddressInterface
     */
    private function getDefaultAddress(Quote $quote, string $type): AddressInterface|QuoteAddressInterface
    {
        $address = null;
        if ($customerId = $quote->getCustomerId()) {
            try {
                $address = $type == 'billing' ?
                    $this->accountManagement->getDefaultBillingAddress($customerId) :
                    $this->accountManagement->getDefaultShippingAddress($customerId);
            } catch (NoSuchEntityException|LocalizedException $e) {
            }
        }

        if (!$address) {
            $address = $type == 'billing' ? $quote->getBillingAddress() : $quote->getShippingAddress();
        }
        return $address;
    }
    
    /**
     * Retrieve customer address data
     *
     * @param AddressInterface|QuoteAddressInterface $address
     * @return array
     */
    private function getCustomerAddress(AddressInterface|QuoteAddressInterface $address): array
    {
        $region = $address->getRegion();
        $street = $address->getStreet();
        $countryId = $address->getCountryId();
        $region = $region ? is_string($region) ? $region : $region->getRegion() : null;
        $countryName =  null;

        if ($countryId) {
            try {
                $country = $this->countryInformation->getCountryInfo($countryId);
                $countryName = $country->getFullNameEnglish();
            } catch (NoSuchEntityException $e) {
            }
        }

        return [
            "externalId" => $address->getId(),
            "firstName" => $address->getFirstName(),
            "lastName" => $address->getLastName(),
            "company" => $address->getCompany(),
            "address1" => $street[0] ?: null,
            "address2" => $street[1] ?? null,
            "city" => $address->getCity(),
            "country" => $countryName,
            "countryCode" => $countryId,
            "province" => $region,
            "zip" => $address->getPostcode(),
            "phone" => $address->getTelephone()
        ];
    }
    
    /**
     * Retrieve config data
     *
     * @return array
     */
    private function getConfigData(): array
    {
        return [
            "enabled" => $this->rallyConfig->isEnabled(),
            "sandbox" => $this->rallyConfig->isSandboxMode(),
            "clientId" => $this->rallyConfig->getClientId()
        ];
    }
}
