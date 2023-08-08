<?php

declare(strict_types=1);

namespace Rally\Checkout\Model\Queue\Product;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Product\Type;
use Rally\Checkout\Model\Queue\QueueData;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Api\Data\ProductInterface;
use Rally\Checkout\Api\ProductsManagerInterface;
use Rally\Checkout\Api\Data\ProductsDataInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Rally\Checkout\Api\Service\HookManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

/**
 * Consumer for export message.
 */
class Consumer
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Configurable $configurable,
        private readonly HookManagerInterface $hookManager,
        private readonly ProductsManagerInterface $productManager,
        private readonly ExtensibleDataObjectConverter $dataObjectConverter,
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * Process product queue
     *
     * @param QueueData $productQueueData
     * @return void
     * @throws Exception
     */
    public function process(QueueData $productQueueData): void
    {
        try {
            $productData = null;
            $url = 'webhooks/product-update';
            $productId = $productQueueData->getId();
            if ($productQueueData->getAction() == 'delete') {
                $url = 'webhooks/product-delete';
                $productData = [
                    "external_id" => $productId,
                    "organization_id" => ""
                ];
            } elseif ($productQueueData->getAction() == 'update') {
                $product = $this->productRepository->getById($productId);
                $productData = $this->getUpdateWebhookData($product);
            } elseif ($productQueueData->getAction() == 'massUpdate' && $ids = $productQueueData->getIds()) {
                foreach ($ids as $productId) {
                    $product = $this->productRepository->getById($productId);
                    $productData = $this->getUpdateWebhookData($product);

                    if (!$productData) {
                        continue;
                    }
                    $this->hookManager->sendWebhookRequest($url, $productData);
                }
                return;
            }

            if ($productData) {
                $this->hookManager->sendWebhookRequest($url, $productData);
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * Get product update webhook data
     *
     * @param ProductInterface $product
     * @return array|null
     * @throws NoSuchEntityException
     */
    private function getUpdateWebhookData(ProductInterface $product): ?array
    {
        $productData = null;
        $productId = $product->getId();
        $parentIds = $this->configurable->getParentIdsByChild($productId);
        if ($parentIds && isset($parentIds[0])) {
            $product = $this->productRepository->getById($parentIds[0]);
        }

        $status = $product->getStatus();
        $visibility = $product->getVisibility();
        $productType = [Type::TYPE_SIMPLE, Configurable::TYPE_CODE];
        if ($status != Status::STATUS_DISABLED &&
            $visibility != Visibility::VISIBILITY_NOT_VISIBLE &&
            in_array($product->getTypeId(), $productType)
        ) {
            $productData = $this->productManager->getProductData("", $product);
            $productData = $this->dataObjectConverter->toNestedArray($productData, [], ProductsDataInterface::class);
        }
        return $productData;
    }
}
