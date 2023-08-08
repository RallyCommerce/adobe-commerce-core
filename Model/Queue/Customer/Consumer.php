<?php

declare(strict_types=1);

namespace Rally\Checkout\Model\Queue\Customer;

use Exception;
use Psr\Log\LoggerInterface;
use Rally\Checkout\Model\Queue\QueueData;
use Magento\Sales\Api\OrderCustomerManagementInterface;

/**
 * Consumer for customer account create
 */
class Consumer
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly OrderCustomerManagementInterface $orderCustomerService
    ) {
    }

    /**
     * Process create customer queue
     *
     * @param QueueData $orderData
     * @return void
     * @throws Exception
     */
    public function process(QueueData $orderData): void
    {
        try {
            $orderId = $orderData->getId();
            if ($orderData->getAction() == 'create') {
                $this->orderCustomerService->create($orderId);
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
