<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally checkout module observer
 */
class OrderUpdateWebhookObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.order.action.webhook';

    /**
     * Order status webhook
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        $order = $observer->getEvent()->getOrder();
        if ($order) {
            $orderId = $order->getId();
            $createdTime = strtotime($order->getCreatedAt());
            $updatedTime = strtotime($order->getUpdatedAt());
            $timeDifference = ($updatedTime - $createdTime) > 60;
        } else {
            $orderId = $observer->getEvent()->getOrderId();
            $timeDifference = true;
        }

        if (!$this->rallyConfig->isEnabled() || !$orderId || !$timeDifference) {
            return;
        }
        $this->publish(self::TOPIC_NAME, $orderId, 'update');
    }
}
