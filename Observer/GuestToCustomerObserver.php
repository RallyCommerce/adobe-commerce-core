<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally module observer
 */
class GuestToCustomerObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.create.customer.action';

    /**
     * Guest to customer
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        if (!$this->rallyConfig->isEnabled()) {
            return;
        }

        $order = $observer->getEvent()->getOrder();
        if ($order->getId() && !$order->getCustomerId() && $order->getCustomerIsGuest()) {
            $this->publish(self::TOPIC_NAME, $order->getId(), 'create');
        }
    }
}
