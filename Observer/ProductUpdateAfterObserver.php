<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

/**
 * Rally module observer
 */
class ProductUpdateAfterObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.product.action.webhook';
    private const INVENTORY_TOPIC_NAME = 'rally.inventory.action.webhook';

    /**
     * Product update webhook
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        $product = $observer->getEvent()->getProduct();
        $status = $product->getStatus();
        $productId = $product->getId();

        if (!$this->rallyConfig->isEnabled() ||
            !$productId ||
            $product->isObjectNew() ||
            $status == Status::STATUS_DISABLED
        ) {
            return;
        }

        $this->publish(self::TOPIC_NAME, $productId, 'update');
        $this->publish(self::INVENTORY_TOPIC_NAME, $productId, 'inventory');
    }
}
