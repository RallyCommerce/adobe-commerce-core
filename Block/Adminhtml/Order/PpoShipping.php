<?php

namespace Rally\Checkout\Block\Adminhtml\Order;

use Magento\Backend\Block\Template;
use Magento\Sales\Model\Order\Item;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Adminhtml sales order view Rally PPO shipping block
 */
class PpoShipping extends Template
{
    public function __construct(
        protected Template\Context $context,
        protected Json $serializer,
        protected PriceCurrencyInterface $priceCurrency,
        array $data = [],
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get order item object from parent block
     *
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->getParentBlock()->getData('item');
    }

    public function getShippingCosts()
    {
        $order = $this->getItem()->getOrder();
        $shippingCosts = $order->getShippingCosts();

        return $shippingCosts ? $this->serializer->unserialize($shippingCosts) : [];
    }

    /**
     * Get formatted price
     *
     * @param string $price
     * @return string
     */
    public function getFormattedPrice(string $price): string
    {
        $order = $this->getItem()->getOrder();
        $symbol = $order->getOrderCurrencyCode();
        return $this->priceCurrency->format($price, false, 2, null, $symbol);
    }
}
