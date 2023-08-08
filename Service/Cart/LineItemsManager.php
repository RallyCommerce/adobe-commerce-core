<?php

namespace Rally\Checkout\Service\Cart;

use Magento\Framework\App\Area;
use Magento\Catalog\Helper\Image;
use Magento\Bundle\Model\Product\Type;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Store\Model\App\Emulation;
use Magento\Quote\Model\Quote\Item\Repository;
use Magento\Framework\Exception\InputException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Rally\Checkout\Api\Data\LineItemsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Model\Product\Type as ProductType;
use Rally\Checkout\Api\Data\CategoriesDataInterfaceFactory;
use Rally\Checkout\Api\Data\LineItemsOptionsInterfaceFactory;
use Magento\Downloadable\Model\Product\Type as DownloadableType;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;

class LineItemsManager
{
    public function __construct(
        protected Image $imageHelper,
        protected Emulation $appEmulation,
        protected Repository $quoteItemRepository,
        protected PriceCurrencyInterface $currencyConverter,
        protected LineItemsInterfaceFactory $lineItemsFactory,
        protected ProductRepositoryInterface $productRepository,
        protected GetSalableQuantityDataBySku $getSalableQtyDataBySku,
        protected CategoriesDataInterfaceFactory $categoriesDataFactory,
        protected LineItemsOptionsInterfaceFactory $lineItemsOptionsFactory
    ) {
    }

    /**
     * Get customer cart data
     *
     * @param CartInterface $quote
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCartData(CartInterface $quote): array
    {
        return $this->getLineItemData($quote, "hook");
    }

    /**
     * Update cart line item data
     *
     * @param CartInterface $quote
     * @param array $rallyCartData
     * @return void
     * @throws CouldNotSaveException
     * @throws InputException
     * @throws NoSuchEntityException
     */
    public function updateCartData(CartInterface $quote, array $rallyCartData): void
    {
        if (!empty($rallyCartData['line_items'])) {
            foreach ($rallyCartData['line_items'] as $item) {
                $quoteItem = $quote->getItemById($item['external_id']);

                if ($quoteItem && $quoteItem->getQty() != $item['quantity']) {
                    $quoteItem->setQty($item['quantity']);

                    $this->quoteItemRepository->save($quoteItem);
                }
            }
        }
    }

    /**
     * Get order data
     *
     * @param OrderInterface $order
     * @return array
     * @throws NoSuchEntityException
     */
    public function getOrderData(OrderInterface $order): array
    {
        return $this->getLineItemData($order, "webhook");
    }

    /**
     * Get cart line item data
     *
     * @param CartInterface|OrderInterface $cart
     * @param string $type
     * @return array
     * @throws NoSuchEntityException
     */
    private function getLineItemData(CartInterface|OrderInterface $cart, string $type): array
    {
        $counter = 0;
        $itemsData = [];
        $storeId = $cart->getStoreId();
        $items = $cart->getAllVisibleItems();

        foreach ($items as $item) {
            $itemOptions = $itemsCategoryIds = [];
            $product = $item->getProduct();
            $variantId = $product->getId();
            $options = $type == "hook" ? $product->getTypeInstance()->getOrderOptions($product)
                : $item->getProductOptions();

            if (isset($options['simple_sku'])) {
                $variantId = $this->productRepository->get($options['simple_sku'])->getId();
            }

            if (!empty($options['attributes_info'])) {
                foreach ($options['attributes_info'] as $key => $option) {
                    if ($type == "hook") {
                        $lineItemsOptionsData = $this->lineItemsOptionsFactory->create();
                        $lineItemsOptionsData->setExternalId($option['option_id'])
                            ->setName($option['label'])
                            ->setValue($option['value'])
                            ->setPosition($key);
                    } else {
                        $lineItemsOptionsData = [
                            "external_id" => $option['option_id'],
                            "name" => $option['label'],
                            "value" => $option['value'],
                            "position" => $key
                        ];
                    }
                    $itemOptions[] = $lineItemsOptionsData;
                }
            }

            $categoryIds = $product->getCategoryIds();
            $imageUrl = $product->getData('small_image');

            foreach ($categoryIds as $categoryId) {
                if ($type == "hook") {
                    $categoriesData = $this->categoriesDataFactory->create();
                    $categoriesData->setExternalId($categoryId);
                } else {
                    $categoriesData = [
                        "external_id" =>  $categoryId
                    ];
                }
                $itemsCategoryIds[] = $categoriesData;
            }

            if ($imageUrl === null || $imageUrl == 'no_selection') {
                $this->appEmulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);
                $imageUrl = $this->imageHelper->getDefaultPlaceholderUrl('thumbnail');
                $this->appEmulation->stopEnvironmentEmulation();
            } else {
                $imageUrl = $product->getMediaConfig()->getMediaUrl($imageUrl);
            }

            try {
                $stockData = $this->getSalableQtyDataBySku->execute($product->getSku());
                $productQty = array_sum(array_column($stockData, 'qty'));
            } catch (\Exception $e) {
                $productQty = 0;
            }

            $itemPrice = $product->getPrice() ?: $item->getPrice();
            $finalPrice = $item->getCustomPrice() ?? $product->getFinalPrice();
            $storeCurrencyCode = $cart->getStoreCurrencyCode();
            $cartCurrencyCode = $type == "hook" ? $cart->getQuoteCurrencyCode() : $cart->getOrderCurrencyCode();

            if ($storeCurrencyCode != $cartCurrencyCode) {
                $store = $cart->getStore();
                $itemPrice = $this->currencyConverter->convertAndRound($itemPrice, $store, $cartCurrencyCode);
                $finalPrice = $this->currencyConverter->convertAndRound($finalPrice, $store, $cartCurrencyCode);
            }

            $productTypes = [ProductType::TYPE_VIRTUAL, DownloadableType::TYPE_DOWNLOADABLE];
            $shippingRequired = !in_array($product->getTypeId(), $productTypes);
            $discountedPrice = $finalPrice ?: $itemPrice;
            $discountAmount = $this->getItemDiscount($item, $type);
            $taxRate = $item->getTaxPercent() ?? $this->getItemTaxRate($item, $type);
            $total = $item->getRowTotal() + $item->getTaxAmount() - $discountAmount;

            if ($type == "hook") {
                $lineItemsData = $this->lineItemsFactory->create();
                $lineItemsData->setExternalId($item->getId())
                    ->setExternalProductId($product->getId())
                    ->setExternalVariantId($variantId)
                    ->setQuantity($item->getQty())
                    ->setTitle($item->getName())
                    ->setExternalVendor("")
                    ->setExternalSku($item->getSku())
                    ->setImage($imageUrl)
                    ->setInventoryQuantity($productQty)
                    ->setRequiresShipping($shippingRequired)
                    ->setTaxable($item->getTaxAmount() > 0)
                    ->setOptions($itemOptions)
                    ->setPosition($counter)
                    ->setPrice($itemPrice)
                    ->setDiscountedPrice((float) $discountedPrice)
                    ->setTaxRate((float) ($taxRate / 100))
                    ->setTaxAmount($item->getTaxAmount())
                    ->setSubtotal((float) $item->getRowTotal())
                    ->setTotal((float) $total)
                    ->setDiscountAmount((float) $discountAmount)
                    ->setIsRemovable(false)
                    ->setCategories($itemsCategoryIds)
                    ->setMetafields([]);
            } else {
                $lineItemsData = [
                    "external_id" => $item->getItemId(),
                    "external_product_id" => $product->getId(),
                    "external_variant_id" => $variantId,
                    "title" => $item->getName(),
                    "external_vendor" => "",
                    "external_sku" => $item->getSku(),
                    "image" => $imageUrl,
                    "inventory_quantity" => $productQty,
                    "requires_shipping" => $shippingRequired,
                    "position" => $counter,
                    "quantity" => (int) $item->getQtyOrdered(),
                    "taxable" => $item->getTaxAmount() > 0,
                    "options" => $itemOptions,
                    "categories" => $itemsCategoryIds,
                    "price" => (float) $itemPrice,
                    "discounted_price" => (float) $discountedPrice,
                    "tax_rate" => (float) ($taxRate / 100),
                    "tax_amount" => (float) $item->getTaxAmount(),
                    "subtotal" => (float) $item->getRowTotal(),
                    "total" => (float) $total,
                    "discount_amount" => (float) $discountAmount,
                    "meta" => []
                ];
            }
            $itemsData[] = $lineItemsData;
            $counter++;
        }
        return $itemsData;
    }

    /**
     * Get item discount amount
     *
     * @param Item|OrderItemInterface $item
     * @param string $type
     * @return float|null
     */
    private function getItemDiscount(Item|OrderItemInterface $item, string $type): ?float
    {
        $productType = $item->getProductType();
        $discountAmount = 0;

        if ($productType == Type::TYPE_CODE) {
            $children = $type == "hook" ? $item->getChildren() : $item->getChildrenItems();
            foreach ($children as $child) {
                $discountAmount += $child->getDiscountAmount();
            }
        } else {
            $discountAmount = $item->getDiscountAmount();
        }
        return $discountAmount;
    }

    /**
     * Get item tax rate
     *
     * @param Item|OrderItemInterface $item
     * @param string $type
     * @return float
     */
    private function getItemTaxRate(Item|OrderItemInterface $item, string $type): float
    {
        $taxRate = 0;
        $productType = $item->getProductType();

        if ($productType == Type::TYPE_CODE) {
            $children = $type == "hook" ? $item->getChildren() : $item->getChildrenItems();
            foreach ($children as $child) {
                $taxRate += $child->getTaxPercent();
            }
            $taxRate = $taxRate / count($children);
        } else {
            $taxablePrice = $item->getRowTotal() - $item->getDiscountAmount();
            $taxRate = $taxablePrice > 0 ? number_format((100 * $item->getTaxAmount()) / $taxablePrice, 3) : 0;
        }
        return $taxRate;
    }
}
