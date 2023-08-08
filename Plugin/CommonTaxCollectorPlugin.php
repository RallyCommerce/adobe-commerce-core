<?php

declare(strict_types=1);

namespace Rally\Checkout\Plugin;

use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Tax\Api\Data\QuoteDetailsItemInterface;
use Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory;
use Magento\Tax\Model\Sales\Total\Quote\CommonTaxCollector;

/**
 * Plugin for CommonTaxCollector to remove Tax Class ID
 */
class CommonTaxCollectorPlugin
{
    /**
     * Remove Tax Class ID for PPO item
     *
     * @param CommonTaxCollector $subject
     * @param QuoteDetailsItemInterface $result
     * @param QuoteDetailsItemInterfaceFactory $itemDataObjectFactory
     * @param AbstractItem $item
     * @return QuoteDetailsItemInterface
     */
    public function afterMapItem(
        CommonTaxCollector $subject,
        QuoteDetailsItemInterface $result,
        QuoteDetailsItemInterfaceFactory $itemDataObjectFactory,
        AbstractItem $item
    ) : QuoteDetailsItemInterface {
        if ($item->getIsPpo()) {
            $result->getTaxClassKey()->setValue(0);
        }

        return $result;
    }
}
