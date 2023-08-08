<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally store config save observer
 */
class StoreConfigSaveObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.config.action.webhook';

    /**
     * Rally store info webhook
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        $eventData = $observer->getEvent();
        $changedPaths = (array) $eventData->getChangedPaths();

        if (!empty($changedPaths) && $this->rallyConfig->isEnabled()) {
            $this->publish(self::TOPIC_NAME, [$eventData->getWebsite(), $eventData->getStore()], 'update');
        }
    }
}
