<?php

namespace Rally\Checkout\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Rally checkout module observer
 */
class RefundOrderWebhookObserver extends RallyAbstractObserver
{
    private const TOPIC_NAME = 'rally.order.action.webhook';

    /**
     * Refund order webhook
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        if (!$this->rallyConfig->isEnabled()) {
            return;
        }

        $creditMemo = $observer->getEvent()->getCreditmemo();
        $this->publish(self::TOPIC_NAME, $creditMemo->getId(), 'refund');
    }
}
