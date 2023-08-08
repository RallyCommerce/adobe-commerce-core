<?php

declare(strict_types=1);

namespace Rally\Checkout\Model\Queue\Inventory;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Bundle\Model\Product\Type;
use Rally\Checkout\Model\Queue\QueueData;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Rally\Checkout\Api\Service\HookManagerInterface;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;

/**
 * Consumer for inventory update webhook
 */
class Consumer
{
    public function __construct(
        private readonly Type $bundleType,
        private readonly Grouped $groupType,
        private readonly LoggerInterface $logger,
        private readonly Configurable $configurable,
        private readonly HookManagerInterface $hookManager,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly GetSalableQuantityDataBySku $getSalableQtyDataBySku,
        private readonly OrderItemRepositoryInterface $orderItemRepository
    ) {
    }

    /**
     * Process inventory queue data
     *
     * @param QueueData $productData
     * @return void
     * @throws Exception
     */
    public function process(QueueData $productData): void
    {
        try {
            $url = 'webhooks/product-inventory-update';
            if ($productData->getAction() == 'inventories' && $items = $productData->getIds()) {
                foreach ($items as $itemId) {
                    $item = $this->orderItemRepository->get($itemId);
                    $inventoryData = $this->getInventoryWebhookData($item);
                    $this->hookManager->sendWebhookRequest($url, $inventoryData);
                }
            } elseif ($productData->getAction() == 'inventory') {
                $productId = $productData->getId();
                $product = $this->productRepository->getById($productId);
                $inventoryData = $this->getInventoryWebhookData($product, 'single');
                $this->hookManager->sendWebhookRequest($url, $inventoryData);
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * Get inventory data for webhook
     *
     * @param OrderItemInterface|ProductInterface $item
     * @param string $type
     * @return array|null
     */
    private function getInventoryWebhookData(OrderItemInterface|ProductInterface $item, string $type = ''): ?array
    {
        $inventoryData = null;
        try {
            $product = $type == 'single' ? $item : $item->getProduct();
            $stockData = $this->getSalableQtyDataBySku->execute($product->getSku());
            $quantity = array_sum(array_column($stockData, 'qty'));
        } catch (\Exception $e) {
            $quantity = null;
        }

        if ($quantity != null) {
            $ids = $this->getProductIds($item, $type);
            $inventoryData = [
                "external_product_id" => $ids[0],
                "external_variant_id" => $ids[1],
                "organization_id" => "",
                "value" => (int) $quantity,
                "method" => "absolute"
            ];
        }
        return $inventoryData;
    }

    /**
     * Get product ids
     *
     * @param OrderItemInterface|ProductInterface $item
     * @param string $type
     * @return array
     */
    private function getProductIds(OrderItemInterface|ProductInterface $item, string $type): array
    {
        if (!$type) {
            $productId = $item->getProductId();
            $parentItem = $item->getParentItem();

            if ($item->getProductType() == "grouped") {
                $parentIds = $this->groupType->getParentIdsByChild($productId);
            } elseif ($parentItem && $parentItem->getProductType() == "bundle") {
                $parentIds = $this->bundleType->getParentIdsByChild($productId);
            } elseif ($parentItem && $parentItem->getProductType() == "configurable") {
                $parentIds = $this->configurable->getParentIdsByChild($productId);
            } else {
                $parentIds = null;
            }
        } else {
            $productId = $item->getId();
            $parentIds = $this->configurable->getParentIdsByChild($productId);

            if (empty($parentIds)) {
                $parentIds = $this->groupType->getParentIdsByChild($productId);
            }

            if (empty($parentIds)) {
                $parentIds = $this->bundleType->getParentIdsByChild($productId);
            }
        }

        $parentId = $parentIds ? $parentIds[0] : $productId;
        $variantId = $parentIds ? $productId : "";
        return [$parentId, $variantId];
    }
}
