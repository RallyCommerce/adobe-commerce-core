<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally module observer
 */
class CategoryDeleteAfterObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.category.action.webhook';

    /**
     * Category delete webhook
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        $category = $observer->getEvent()->getCategory();
        $categoryId = $category->getId();

        if (!$this->rallyConfig->isEnabled() || !$categoryId) {
            return;
        }

        $this->publish(self::TOPIC_NAME, $categoryId, 'delete');
    }
}
