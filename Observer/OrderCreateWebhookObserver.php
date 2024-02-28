<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally checkout module observer
 */
class OrderCreateWebhookObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.order.action.webhook';

    /**
     * Order create webhook
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        if (!$this->rallyConfig->isEnabled()) {
            return;
        }

        $eventData = $observer->getEvent();
        if ($order = $eventData->getOrder()) {
            $this->publish(self::TOPIC_NAME, $order->getId(), 'create');
        } elseif ($orders = $eventData->getOrders()) {
            foreach ($orders as $order) {
                $this->publish(self::TOPIC_NAME, $order->getId(), 'create');
            }
        }
    }
}
