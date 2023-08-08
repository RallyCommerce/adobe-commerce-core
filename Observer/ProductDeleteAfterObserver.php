<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally module observer
 */
class ProductDeleteAfterObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.product.action.webhook';

    /**
     * Product delete webhook
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        $product = $observer->getEvent()->getProduct();
        $productId = $product->getId();

        if (!$this->rallyConfig->isEnabled() || !$productId) {
            return;
        }

        $this->publish(self::TOPIC_NAME, $productId, 'delete');
    }
}
