<?php

declare(strict_types=1);

namespace Rally\Checkout\Model\Queue\Category;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Category\Image;
use Rally\Checkout\Model\Queue\QueueData;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Rally\Checkout\Api\Service\HookManagerInterface;

/**
 * Consumer for category webhook
 */
class Consumer
{
    public function __construct(
        private readonly Image $image,
        private readonly LoggerInterface $logger,
        private readonly HookManagerInterface $hookManager,
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * Process category queue
     *
     * @param QueueData $categoryQueueData
     * @return void
     * @throws Exception
     */
    public function process(QueueData $categoryQueueData): void
    {
        try {
            $categoryId = $categoryQueueData->getId();

            if ($categoryQueueData->getAction() == 'delete') {
                $url = 'webhooks/category-delete';
                $categoryData = [
                    "external_id" => $categoryId,
                    "organization_id" => ""
                ];
            } else {
                $url = 'webhooks/category-update';
                $category = $this->categoryRepository->get($categoryId);
                $categoryImage = $this->image->getUrl($category);
                $categoryData = [
                    "organization_id" => "",
                    "title" => $category->getName(),
                    "external_id" => $categoryId,
                    "image_url" => $categoryImage,
                    "meta" => ""
                ];
            }
            $this->hookManager->sendWebhookRequest($url, $categoryData);
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
