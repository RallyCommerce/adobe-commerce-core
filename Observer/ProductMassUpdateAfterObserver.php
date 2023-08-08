<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally module observer
 */
class ProductMassUpdateAfterObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.product.action.webhook';

    /**
     * Product mass update webhook
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        $event = $observer->getEvent();
        $attrData = $event->getAttributesData();
        $productIds = $event->getProductIds();

        if (!$this->rallyConfig->isEnabled() || !$productIds || empty($attrData)) {
            return;
        }

        $this->publish(self::TOPIC_NAME, $productIds, 'massUpdate');
    }
}
