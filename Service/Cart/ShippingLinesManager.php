<?php

namespace Rally\Checkout\Service\Cart;

use Magento\Tax\Helper\Data;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\StateException;
use Rally\Checkout\Service\Customer\ResponseMapper;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Rally\Checkout\Api\Data\ShippingLinesDataInterfaceFactory;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory;
use Magento\Directory\Api\CountryInformationAcquirerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote\Address\RateCollectorInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\ShippingInformationManagementInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateRequestFactory;

class ShippingLinesManager
{
    public function __construct(
        protected Data $taxHelper,
        protected Rate $addressRate,
        protected ResponseMapper $responseMapper,
        protected DirectoryHelper $directoryHelper,
        protected CollectionFactory $collectionFactory,
        protected ShippingMethodManagementInterface $shippingMethod,
        protected ShippingLinesDataInterfaceFactory $shippingLinesDataFactory,
        protected CountryInformationAcquirerInterface $countryInformation,
        protected RateCollectorInterface $rateCollector,
        protected StoreManagerInterface $storeManager,
        protected PriceCurrencyInterface $currencyConverter,
        protected ShippingInformationInterface $shippingInformation,
        protected ShippingInformationManagementInterface $shippingManagement,
        protected RateRequestFactory $rateRequestFactory
    ) {
    }

    /**
     * Get cart shipping lines data
     *
     * @param CartInterface $quote
     * @return array
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getCartData(CartInterface $quote): array
    {
        $itemQty = 0;
        $shippingList = [];
        $shippingAddress = $quote->getShippingAddress();
        $flag = $freeShipping = $shippingAddress->getFreeShipping();
        foreach ($quote->getAllItems() as $item) {
            $flag = $item->getFreeShipping() ? 0 : $flag;
            $item->setFreeShippingMethod(null);
            $item->setFreeShipping(false);
            if (!$item->getProduct()->isVirtual() && !$item->getParentItem()) {
                $itemQty += $item->getQty();
            }
        }

        try {
            $region = $shippingAddress->getRegion();
            $isRegionRequired = $this->directoryHelper->isRegionRequired($shippingAddress->getCountryId() ?? "");
            if (
                $shippingAddress->getPostcode() &&
                $shippingAddress->getCountryId() &&
                !$quote->getHasCountryError() &&
                !($isRegionRequired && !$region)
            ) {
                $shippingAddress->setFreeShipping($flag)->setItemQty($itemQty);
                $request = $this->getRateRequest($shippingAddress);
                $rates = $this->rateCollector->collectRates($request);
                $shippingList = $rates->getResult()->getAllRates();
            }
        } catch (\Exception $e) {
        }

        $shippingMethodData = [];
        $cheapestMethods = [];
        foreach ($shippingList as $shipping) {
            $shippingPrice = $shipping->getPrice();
            $storeCurrencyCode = $quote->getStoreCurrencyCode();
            $cartCurrencyCode = $quote->getQuoteCurrencyCode();

            if ($storeCurrencyCode != $cartCurrencyCode) {
                $store = $quote->getStore();
                $shippingPrice = $this->currencyConverter->convertAndRound($shippingPrice, $store, $cartCurrencyCode);
            }

            $carrierCode = $shipping->getCarrier();
            $methodCode = $shipping->getMethod();
            $carrierTitle = $shipping->getCarrierTitle();
            $methodTitle = $shipping->getMethodTitle();
            $inclTax = $this->getShippingPriceWithFlag($quote, $shippingPrice, true);
            $exclTax = $this->getShippingPriceWithFlag($quote, $shippingPrice, false);
            $taxRate = $exclTax ? ($inclTax - $exclTax) / $exclTax : 0;
            $externalId = $carrierCode . "_" . $methodCode;
            $cheapestMethods[$externalId] = $exclTax;

            $shippingData = $this->shippingLinesDataFactory->create();
            $shippingData->setExternalId($externalId)
                ->setTitle($carrierTitle)
                ->setCarrierIdentifier($carrierCode)
                ->setCode($methodCode)
                ->setDescription($methodTitle)
                ->setPrice($shippingPrice)
                ->setSubtotal($shippingPrice)
                ->setTaxRate(round($taxRate, 4))
                ->setTaxAmount($inclTax - $exclTax)
                ->setTotal($inclTax);

            $shippingMethodData[$externalId] = $shippingData;
        }

        $selectedMethod = $shippingAddress->getShippingMethod();
        $isAvailable = array_key_exists($selectedMethod, $cheapestMethods);
        if (!empty($cheapestMethods) && (!$selectedMethod || !$isAvailable)) {
            $cheapestMethod = (string) array_search(min($cheapestMethods), $cheapestMethods);
            $this->addressRate->setCode($cheapestMethod);

            $shippingAddress->setCollectShippingRates(true)
                ->collectShippingRates()
                ->setShippingMethod($cheapestMethod);

            $quote->getShippingAddress()->addShippingRate($this->addressRate);

            if (!$isAvailable) {
                $shippingCode = explode("_", $cheapestMethod);
                $addressInformation = $this->shippingInformation->setShippingAddress($shippingAddress)
                    ->setBillingAddress($quote->getBillingAddress())
                    ->setShippingMethodCode($shippingCode[1])
                    ->setShippingCarrierCode($shippingCode[0]);

                $this->shippingManagement->saveAddressInformation($quote->getId(), $addressInformation);
            }
        }
        $selectedMethod = $shippingAddress->getShippingMethod();
        $quote->setRallyShippingAmount($cheapestMethods[$selectedMethod] ?? 0);

        if (isset($shippingMethodData[$selectedMethod])) {
            $shippingTax = $shippingAddress->getShippingTaxAmount();
            $shippingData = $shippingMethodData[$selectedMethod];
            $total = $shippingAddress->getShippingAmount() == 0 && $freeShipping ?
                0 : $shippingTax + $shippingData->getSubtotal() - $shippingAddress->getShippingDiscountAmount();
            $shippingData->setTaxAmount($shippingTax);
            $shippingData->setTotal($total);
            $quote->setRallyShippingAmount($quote->getRallyShippingAmount() ?: $shippingData->getPrice());
        }
        return $shippingMethodData;
    }

    /**
     * Update cart shipping data
     *
     * @param CartInterface $quote
     * @param array $rallyCartData
     * @return void
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function updateCartData(CartInterface $quote, array $rallyCartData): void
    {
        $rallyCustomerData = $rallyCartData['customer'];

        if (isset($rallyCustomerData['email'])) {
            $customerEmail = $rallyCustomerData['email'];
            $customerFirstName = $rallyCustomerData['first_name'];
            $customerLastName = $rallyCustomerData['last_name'];

            $quote->setCustomerEmail($customerEmail)
                ->setCustomerFirstname($customerFirstName)
                ->setCustomerLastname($customerLastName);
        }

        $shippingAddress = $quote->getShippingAddress();
        $rallyCustomerData = isset($rallyCustomerData['billing_address']) ? $rallyCustomerData : $rallyCartData;
        $this->getAddressData($rallyCustomerData, 'billing_address', $quote);
        $this->getAddressData($rallyCustomerData, 'shipping_address', $quote);

        if (!empty($rallyCartData['selected_shipping_line_external_id']) &&
            $shippingAddress->getShippingMethod() != $rallyCartData['selected_shipping_line_external_id'] &&
            $shippingAddress->getPostCode() &&
            $shippingAddress->getCountryId()
        ) {
            $selectedShippingCode = explode("_", $rallyCartData['selected_shipping_line_external_id']);
            $this->shippingMethod->set(
                $quote->getId(),
                $selectedShippingCode[0],
                $selectedShippingCode[1]
            );
        }
    }

    /**
     * Get order shipping data
     *
     * @param OrderInterface $order
     * @param string $type
     * @return array
     * @throws NoSuchEntityException
     */
    public function getOrderData(OrderInterface $order, string $type): array
    {
        $address = ($type == 'shipping') ? $order->getShippingAddress() : $order->getBillingAddress();

        $street = $address->getStreet();
        $countryName = $address->getCountryId() ?
            $this->countryInformation->getCountryInfo($address->getCountryId())->getFullNameEnglish() : "";

        return [
           "external_id" => $address->getEntityId(),
           "address1" => $street[0],
           "address2" => $street[1] ?? "",
           "city" => $address->getCity(),
           "company" => $address->getCompany(),
           "country" => $countryName,
           "country_code" => $address->getCountryId(),
           "first_name" => $address->getFirstName(),
           "last_name" => $address->getLastName(),
           "phone" => $address->getTelephone(),
           "province" => $address->getRegion(),
           "zip" => $address->getPostcode()
        ];
    }

    /**
     * Get customer address data
     *
     * @param array $rallyCustomerData
     * @param string $type
     * @param CartInterface $quote
     * @return void
     */
    private function getAddressData(array $rallyCustomerData, string $type, CartInterface $quote): void
    {
        $rallyAddress = $rallyCustomerData[$type];
        $shippingAddress = $quote->getShippingAddress();
        $addressData = ($type == 'shipping_address') ? $shippingAddress : $quote->getBillingAddress();
        $sameAsBilling = $rallyCustomerData['is_billing_equal_shipping'] ?? $shippingAddress->getSameAsBilling();

        if (!empty($rallyAddress['province'])) {
            $regionCode = $this->collectionFactory->create()
                ->addRegionNameFilter($rallyAddress['province'])
                ->getFirstItem()
                ->toArray();

            $rallyAddress['code'] = $regionCode['code'];
            $rallyAddress['region_id'] = $regionCode['region_id'];
        } else {
            $rallyAddress['code'] = null;
            $rallyAddress['region_id'] = null;
        }

        $rallyAddress['email'] = $quote->getCustomerEmail();
        $rallyAddress['same_as_billing'] = ($type == 'shipping_address') ? $sameAsBilling : false;
        $rallyAddress['save_in_address_book'] = true;
        $this->responseMapper->map($addressData, $rallyAddress);
    }

    /**
     * Get Shipping Price including or excluding tax
     *
     * @param CartInterface $quote
     * @param float $price
     * @param bool $flag
     * @return float
     */
    private function getShippingPriceWithFlag(CartInterface $quote, float $price, bool $flag): float
    {
        $address = $quote->getShippingAddress();

        return $this->taxHelper->getShippingPrice(
            $price,
            $flag,
            $address,
            $quote->getCustomerTaxClassId()
        );
    }

    /**
     * Get rates request
     *
     * @param \Magento\Framework\DataObject $address
     * @param null|array $limitCarrier
     * @return RateRequest
     */
    public function getRateRequest(\Magento\Framework\DataObject $address, $limitCarrier = null)
    {
        $request = $this->rateRequestFactory->create();
        $request->setAllItems($address->getAllItems());
        $request->setDestCountryId($address->getCountryId());
        $request->setDestRegionId($address->getRegionId());
        $request->setDestPostcode($address->getPostcode());
        $request->setPackageValue($address->getBaseSubtotal());
        $request->setPackageValueWithDiscount($address->getBaseSubtotalWithDiscount());
        $request->setPackageWeight($address->getWeight());
        $request->setFreeMethodWeight($address->getFreeMethodWeight());
        $request->setPackageQty($address->getItemQty());
        $request->setFreeShipping($address->getFreeShipping());

        $store = $this->storeManager->getStore();
        $request->setStoreId($store->getId());
        $request->setWebsiteId($store->getWebsiteId());
        $request->setBaseCurrency($store->getBaseCurrency());
        $request->setPackageCurrency($store->getCurrentCurrency());
        $request->setLimitCarrier($limitCarrier);

        $request->setBaseSubtotalInclTax($address->getBaseSubtotalInclTax());

        return $request;
    }
}
