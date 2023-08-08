<?php

namespace Rally\Checkout\Observer;

use Rally\Checkout\Api\ConfigInterface;
use Magento\Framework\Event\ObserverInterface;
use Rally\Checkout\Model\Queue\QueueDataFactory;
use Magento\Framework\MessageQueue\PublisherInterface;

abstract class RallyAbstractObserver implements ObserverInterface
{
    public function __construct(
        protected ConfigInterface $rallyConfig,
        protected PublisherInterface $publisher,
        protected QueueDataFactory $queueDataFactory
    ) {
    }

    /**
     * Publish queue for webhook call
     *
     * @param string $topic
     * @param string|array $data
     * @param string $action
     * @return void
     */
    protected function publish(string $topic, string|array $data, string $action): void
    {
        $queueData = ['action' => $action];
        if (is_array($data)) {
            $queueData['ids'] = $data;
        } else {
            $queueData['id'] = $data;
        }
        $queueData = $this->queueDataFactory->create($queueData);
        $this->publisher->publish($topic, $queueData);
    }
}
