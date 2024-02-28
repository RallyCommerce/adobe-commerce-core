<?php

namespace Rally\Checkout\Service\Order;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Rally\Checkout\Api\ConfigInterface;
use Rally\Checkout\Api\Data\OrderDataInterfaceFactory;
use Rally\Checkout\Api\Data\ShippingLinesDataInterfaceFactory;
use Rally\Checkout\Api\Service\RequestValidatorInterface;
use Rally\Checkout\Service\CartMapper;

class DataManager
{
    public function __construct(
        protected CartRepositoryInterface $quoteRepository,
        protected OrderRepositoryInterface $orderRepository,
        protected ConfigInterface $rallyConfig,
        protected OrderDataInterfaceFactory $orderDataFactory,
        protected ShippingLinesDataInterfaceFactory $shippingLinesDataFactory,
        protected RequestValidatorInterface $requestValidator,
        protected CartMapper $cartMapper,
        protected array $orderDataMappers = []
    ) {
    }

    /**
     * Get order data
     *
     * @param string $externalId
     *
     * @return \Rally\Checkout\Api\Data\OrderDataInterface
     */
    public function getOrderData(string $externalId)
    {
        $this->requestValidator->validate();

        $orderData = $this->orderDataFactory->create();
        $orderId = $this->rallyConfig->getId($externalId);

        $order = $this->orderRepository->get($orderId);
        $quote = $this->quoteRepository->get($order->getQuoteId());

        $cartId = $this->rallyConfig->getFormattedId('quote', $quote->getId(), $quote->getCreatedAt());
        $orderStatus = $this->cartMapper->getMappedStatus($order->getStatus());
        $shippingCost = $this->getShippingCost($order);

        $orderData->setExternalId($externalId)
            ->setExternalNumber($order->getIncrementId())
            ->setCartId($cartId)
            ->setStatus($orderStatus)
            ->setSubtotal((float) $order->getSubtotal())
            ->setTotal((float) $order->getGrandTotal())
            ->setTaxAmount((float) $order->getTaxAmount())
            ->setShippingLines([$shippingCost])
            ->setShippingCost($shippingCost);

        foreach ($this->orderDataMappers as $method => $orderDataMapper) {
            $orderData->$method($orderDataMapper->getOrderData($order));
        }

        return $orderData;
    }

    private function getShippingCost($order)
    {
        $externalId = $order->getShippingMethod();
        $title = $order->getShippingDescription();
        $shippingCode = explode('_', (string) $externalId);
        $price = (float) $order->getShippingAmount();
        $taxAmount = (float) $order->getShippingTaxAmount();
        $inclTax = (float) $order->getShippingInclTax();
        $taxRate = $price ? ($inclTax - $price) / $price : 0;

        $shippingData = $this->shippingLinesDataFactory->create();
        $shippingData->setExternalId($externalId)
            ->setTitle($title)
            ->setCarrierIdentifier($shippingCode[0] ?? '')
            ->setCode($shippingCode[1] ?? '')
            ->setDescription($title)
            ->setPrice($price)
            ->setSubtotal($price)
            ->setTaxRate(round($taxRate, 4))
            ->setTaxAmount($taxAmount)
            ->setTotal($inclTax);

        return $shippingData;
    }
}
