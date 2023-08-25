<?php

namespace Rally\Checkout\Service;

use Magento\Framework\Webapi\Exception;
use Magento\Quote\Api\Data\CartInterface;
use Rally\Checkout\Api\ConfigInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Rally\Checkout\Api\Data\CartDataInterface;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Rally\Checkout\Api\Data\CartDataInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Quote\Api\ShippingMethodManagementInterface;
use Rally\Checkout\Api\Service\RequestValidatorInterface;
use Magento\Checkout\Api\TotalsInformationManagementInterface;
use Magento\Checkout\Api\Data\TotalsInformationInterfaceFactory;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;
use Magento\Framework\Api\SimpleDataObjectConverter as NameConverter;

class CartMapper
{
    private const ERROR_INSUFFICIENT_STOCK = 'The requested qty is not available';
    private const ERROR_OUT_OF_STOCK = "There are no source items with the in stock status";
    private const ERROR_NOT_SALABLE = "Product that you are trying to add is not available.";

    public array $orderStatusMap = [
        "pending" => "pending",
        "pending_payment" => "pending",
        "fraud" => "pending",
        "payment_review" => "pending",
        "holded" => "pending",
        "canceled" => "pending",
        "processing" => "paid",
        "complete" => "completed",
        "closed" => "refunded"
    ];

    public function __construct(
        public ConfigInterface $rallyConfig,
        public StoreManagerInterface $storeManager,
        public CartManagementInterface $cartManagement,
        public AddressInterfaceFactory $addressFactory,
        public CartRepositoryInterface $quoteRepository,
        public CartDataInterfaceFactory $cartDataFactory,
        public RequestValidatorInterface $requestValidator,
        public ProductRepositoryInterface $productRepository,
        public TotalsInformationInterfaceFactory $totalInfoFactory,
        public GetSalableQuantityDataBySku $getSalableQtyDataBySku,
        public TotalsInformationManagementInterface $totalInfoManagement,
        protected ShippingMethodManagementInterface $shippingManagement,
        public array $cartDataMappers = []
    ) {
    }

    /**
     * Get customer cart data
     *
     * @param string $orgId
     * @param string $externalId
     * @return CartDataInterface
     * @throws Exception|LocalizedException
     */
    public function mapCartsData(string $orgId, string $externalId): CartDataInterface
    {
        $cartData = $this->cartDataFactory->create();

        try {
            $quoteId = $this->rallyConfig->getId($externalId);
            $quote = $this->quoteRepository->getActive($quoteId);
            $currentStore = $this->storeManager->getStore();
            if ($currentStore->getWebsite() != $quote->getStore()->getWebsite()) {
                $this->requestValidator->handleException('cart_not_found');
            } elseif ($currentStore->getId() != $quote->getStoreId()) {
                $this->storeManager->setCurrentStore($quote->getStore());
                $this->storeManager->getStore()->setCurrentCurrencyCode($quote->getQuoteCurrencyCode());
            } else {
                $currentStore->setCurrentCurrencyCode($quote->getQuoteCurrencyCode());
            }
        } catch (NoSuchEntityException $exception) {
            $this->requestValidator->handleException('cart_not_found');
        }

        if ($quote->getIsVirtual()) {
            $this->requestValidator->handleException('cart_contains_only_digital_items');
        }

        $cartData->setOrganizationId($orgId)
            ->setExternalId($externalId)
            ->setNotes($quote->getCustomerNote())
            ->setHandlingTotal(0);

        foreach ($this->cartDataMappers as $method => $cartDataMapper) {
            $cartData->$method($cartDataMapper->getCartData($quote));
        }

        $appliedRuleIds = $quote->getAppliedRuleIds();
        $quote->getShippingAddress()->setAppliedRuleIds('');
        $quote->setAppliedRuleIds('');
        $this->quoteRepository->save($quote->collectTotals());
        $cartTotals = $this->getTotalData($quote);
        $shippingAddress = $quote->getShippingAddress();
        $subtotal = $cartTotals ? $cartTotals->getSubtotal() : $quote->getSubtotal();
        $grandTotal = $cartTotals ? $cartTotals->getGrandTotal() : $quote->getGrandTotal();
        $shippingAmount = $cartTotals ? $cartTotals->getShippingAmount() : $shippingAddress->getShippingAmount();
        $quote->setAppliedRuleIds($appliedRuleIds);
        $this->quoteRepository->save($quote->setTotalsCollectedFlag(true));

        $cartData->setCurrency($quote->getQuoteCurrencyCode())
            ->setSubtotal((float) $subtotal)
            ->setTotal((float) $grandTotal)
            ->setShippingTotal((float) $shippingAmount);
        $cartData->setSelectedShippingLineExternalId($shippingAddress->getShippingMethod());

        return $cartData;
    }

    /**
     * Update customer cart data
     *
     * @param string $orgId
     * @param string $externalId
     * @param array $rallyCartData
     * @return CartDataInterface
     * @throws Exception|LocalizedException
     */
    public function updateCartsData(string $orgId, string $externalId, array $rallyCartData): CartDataInterface
    {
        try {
            $quoteId = $this->rallyConfig->getId($externalId);
            $quote = $this->quoteRepository->getActive($quoteId);
            $currentStore = $this->storeManager->getStore();
            if ($currentStore->getWebsite() != $quote->getStore()->getWebsite()) {
                $this->requestValidator->handleException('cart_not_found');
            } elseif ($currentStore->getId() != $quote->getStoreId()) {
                $this->storeManager->setCurrentStore($quote->getStore());
                $this->storeManager->getStore()->setCurrentCurrencyCode($quote->getQuoteCurrencyCode());
            } else {
                $currentStore->setCurrentCurrencyCode($quote->getQuoteCurrencyCode());
            }
        } catch (NoSuchEntityException $exception) {
            $this->requestValidator->handleException('cart_not_found');
        }

        if ($quote->getIsVirtual()) {
            $this->requestValidator->handleException('cart_contains_only_digital_items');
        }

        foreach ($this->cartDataMappers as $cartDataMapper) {
            if (method_exists($cartDataMapper, 'updateCartData')) {
                $cartDataMapper->updateCartData($quote, $rallyCartData);
            }
        }

        if (isset($rallyCartData['notes'])) {
            $quote->setCustomerNote($rallyCartData['notes']);
        }
        $this->quoteRepository->save($quote->collectTotals());

        return $this->mapCartsData($orgId, $externalId);
    }

    /**
     * Get order data
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderData(OrderInterface $order): array
    {
        $orderData = [];
        foreach ($this->cartDataMappers as $method => $cartDataMapper) {
            if (method_exists($cartDataMapper, 'getOrderData')) {
                $key = NameConverter::camelCaseToSnakeCase(substr($method, 3));

                if ($key == "shipping_lines") {
                    $orderData['billing_address'] = $cartDataMapper->getOrderData($order, "billing");
                    $orderData['shipping_address'] = $cartDataMapper->getOrderData($order, "shipping");
                } else {
                    $orderData[$key] = $cartDataMapper->getOrderData($order);
                }
            }
        }
        return $orderData;
    }

    /**
     * Get mapped order status
     *
     * @param string $orderStatus
     * @return string
     */
    public function getMappedStatus(string $orderStatus): string
    {
        return $this->orderStatusMap[$orderStatus] ?? "pending";
    }

    /**
     * Create new cart with items
     *
     * @param array $rallyCartData
     * @return string
     * @throws Exception
     * @throws NoSuchEntityException
     */
    public function createCartWithItems(array $rallyCartData): string
    {
        try {
            $cartId = $this->cartManagement->createEmptyCart();
            $quote = $this->quoteRepository->getActive($cartId);
        } catch (NoSuchEntityException|CouldNotSaveException $exception) {
            $this->requestValidator->handleException('cart_not_found');
        }
        $this->addProductsToQuote($rallyCartData['line_items'], $quote);
        return $this->rallyConfig->getFormattedId("quote", $quote->getId(), $quote->getCreatedAt());
    }

    /**
     * Add product to cart
     *
     * @param array $products
     * @param CartInterface $quote
     * @param string|null $type
     * @return array
     * @throws Exception|NoSuchEntityException
     */
    public function addProductsToQuote(array $products, CartInterface $quote, string $type = null): array
    {
        $newItems = [];
        foreach ($products as $product) {
            $productId = $product['external_product_id'];
            $variantId = $product['external_variant_id'];

            $parent = $this->productRepository->getById($productId);

            if (!$parent->isSalable()) {
                $this->requestValidator->handleException('out_of_stock');
            }

            $params = [];
            $params['product'] = $parent->getId();
            $params['qty'] = $product['quantity'];
            if ($type) {
                $params['is_ppo'] = true;
            }

            if (!empty($variantId) && $productId != $variantId) {
                if ($parent->getTypeId() != "configurable") {
                    $this->requestValidator->handleException('ppo_add_error');
                }
                $options = [];
                $child = $this->productRepository->getById($variantId);
                $productAttributeOptions = $parent->getTypeInstance()->getConfigurableAttributesAsArray($parent);

                foreach ($productAttributeOptions as $option) {
                    $options[$option['attribute_id']] = $child->getData($option['attribute_code']);
                }
                $params['super_attribute'] = $options;
            }
            $requestInfo = new \Magento\Framework\DataObject($params);

            try {
                $quoteItem = $quote->addProduct($parent, $requestInfo);
            } catch (\Exception $e) {
                $errors = [
                    self::ERROR_INSUFFICIENT_STOCK,
                    self::ERROR_NOT_SALABLE,
                    self::ERROR_OUT_OF_STOCK
                ];

                if (in_array($e->getMessage(), $errors)) {
                    try {
                        $variant = $this->productRepository->getById($variantId);
                        $variantStockData = $this->getSalableQtyDataBySku->execute($variant->getSku());
                        $qty = array_sum(array_column($variantStockData, 'qty'));
                    } catch (\Exception) {
                        $qty = 0;
                    }
                    $hasMeta = !empty($variantId) && $productId != $variantId ? $qty >= 0 : $qty;

                    $stockData = [
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'quantity' => $qty
                    ];

                    $this->requestValidator->handleException('out_of_stock', $hasMeta ? [$stockData] : []);
                } else {
                    $this->requestValidator->handleException('ppo_add_error');
                }
                $quoteItem = null;
            }

            if ($type && $quoteItem && !is_string($quoteItem)) {
                $quoteItem->setNoDiscount(1);
                $quoteItem->setIsPpo(1);
                $quoteItem->setCustomPrice($product['price']);
                $quoteItem->setOriginalCustomPrice($product['price']);
                $quoteItem->getProduct()->setIsSuperMode(true);

                $newItems[] = $quoteItem;
            } elseif (!is_string($quoteItem) && isset($product['price'])) {
                $quoteItem->setCustomPrice($product['price']);
                $quoteItem->setOriginalCustomPrice($product['price']);
                $quoteItem->getProduct()->setIsSuperMode(true);
            } elseif (is_string($quoteItem)) {
                $this->requestValidator->handleException('ppo_add_error');
            }
        }
        $this->quoteRepository->save($quote->collectTotals());

        return $newItems;
    }

    /**
     * Add items to cart
     *
     * @param string $externalId
     * @param array $products
     * @return void
     * @throws Exception|NoSuchEntityException
     */
    public function addItemsToCart(string $externalId, array $products): void
    {
        try {
            $quoteId = $this->rallyConfig->getId($externalId);
            $quote = $this->quoteRepository->getActive($quoteId);
        } catch (NoSuchEntityException $exception) {
            $this->requestValidator->handleException('cart_not_found');
        }

        $this->addProductsToQuote($products, $quote);
        try {
            $this->shippingManagement->set($quote->getId(), "", "");
        } catch (\Exception $e) {
        }
    }

    /**
     * Remove items from cart
     *
     * @param string $externalId
     * @param array $products
     * @return void
     * @throws Exception
     */
    public function removeItemsFromCart(string $externalId, array $products): void
    {
        try {
            $quoteId = $this->rallyConfig->getId($externalId);
            $quote = $this->quoteRepository->getActive($quoteId);
        } catch (NoSuchEntityException $e) {
            $this->requestValidator->handleException('cart_not_found');
        }

        foreach ($products as $product) {
            $itemId = $product['external_id'];
            $item = $quote->getItemById($itemId);

            if ($item && $item->getQty() <= $product['quantity']) {
                $quote->deleteItem($item);
            } elseif ($item) {
                $item->setQty($item->getQty() - $product['quantity']);
            }
        }

        try {
            $this->shippingManagement->set($quote->getId(), "", "");
        } catch (\Exception $e) {
            $this->quoteRepository->save($quote->collectTotals());
        }
    }

    /**
     * Get cart total data
     *
     * @param CartInterface $quote
     * @return TotalsInterface|null
     */
    private function getTotalData(CartInterface $quote): ?TotalsInterface
    {
        $cartTotals = null;
        $shippingAddress = $quote->getShippingAddress();
        $method = $shippingAddress->getShippingMethod();

        if ($method) {
            $addressData = [
                "countryId" => $shippingAddress->getCountryId(),
                "postcode" => $shippingAddress->getPostcode(),
                "region" => $shippingAddress->getRegion(),
                "regionId" => $shippingAddress->getRegionId(),
            ];
            $address = $this->addressFactory->create()->setData($addressData);
            $shippingCode = explode("_", $method);
            $addressInformation = $this->totalInfoFactory->create()->setAddress($address)
                ->setShippingMethodCode($shippingCode[1])
                ->setShippingCarrierCode($shippingCode[0]);
            $cartTotals = $this->totalInfoManagement->calculate($quote->getId(), $addressInformation);
        }
        return $cartTotals;
    }
}
