<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally module observer
 */
class InventoryUpdateAfterObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.inventory.action.webhook';

    /**
     * Inventory update webhook
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        if (!$this->rallyConfig->isEnabled()) {
            return;
        }

        $itemIds = [];
        $eventData = $observer->getEvent();
        $items = $eventData->getPpoItems();
        if ($items == null && !is_array($items)) {
            $items = [];
            if ($order = $eventData->getOrder()) {
                $items = $order->getAllItems();
            } elseif ($orders = $eventData->getOrders()) {
                $items = array_reduce($orders, function ($items, $order) {
                    return array_merge($items, $order->getAllItems());
                }, $items);
            }
        }

        foreach ($items as $item) {
            if ($item->getChildrenItems()) {
                continue;
            }
            $itemIds[] = $item->getId();
        }
        $this->publish(self::TOPIC_NAME, $itemIds, 'inventories');
    }
}
