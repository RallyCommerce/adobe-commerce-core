<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally checkout module observer
 */
class OrderStatusWebhookObserver extends RallyAbstractObserver
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
        if (!$this->rallyConfig->isEnabled()) {
            return;
        }
        $eventData = $observer->getEvent();

        if ($invoice = $eventData->getInvoice()) {
            $orderId = $invoice->getOrderId();
        } elseif ($shipment = $eventData->getShipment()) {
            $orderId = $shipment->getOrderId();
        } elseif ($creditMemo = $eventData->getCreditmemo()) {
            $orderId = $creditMemo->getOrderId();
        } else {
            return;
        }
        $this->publish(self::TOPIC_NAME, $orderId, 'status');
    }
}
