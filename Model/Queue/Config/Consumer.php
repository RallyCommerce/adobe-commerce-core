<?php

declare(strict_types=1);

namespace Rally\Checkout\Model\Queue\Config;

use Exception;
use Psr\Log\LoggerInterface;
use Rally\Checkout\Model\Queue\QueueData;
use Rally\Checkout\Api\Service\HookManagerInterface;

/**
 * Consumer for config webhook
 */
class Consumer
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly HookManagerInterface $hookManager
    ) {
    }

    /**
     * Process config queue
     *
     * @param QueueData $configData
     * @return void
     * @throws Exception
     */
    public function process(QueueData $configData): void
    {
        try {
            if ($configData->getAction() == 'update' && $data = $configData->getIds()) {
                $this->hookManager->setConfigScope($data[0], $data[1]);
                $this->hookManager->sendStoreInfo();
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
