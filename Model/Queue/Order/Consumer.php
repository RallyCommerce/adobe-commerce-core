<?php

declare(strict_types=1);

namespace Rally\Checkout\Model\Queue\Order;

use Exception;
use Psr\Log\LoggerInterface;
use Rally\Checkout\Service\CartMapper;
use Rally\Checkout\Api\ConfigInterface;
use Rally\Checkout\Model\Queue\QueueData;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Rally\Checkout\Api\Service\HookManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Consumer for export message.
 */
class Consumer
{
    public function __construct(
        private readonly CartMapper $cartMapper,
        private readonly LoggerInterface $logger,
        private readonly ConfigInterface $rallyConfig,
        private readonly HookManagerInterface $hookManager,
        private readonly CartRepositoryInterface $quoteRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CreditmemoRepositoryInterface $creditMemoRepository
    ) {
    }

    /**
     * Process order queue
     *
     * @param QueueData $orderData
     * @return void
     * @throws Exception
     */
    public function process(QueueData $orderData): void
    {
        try {
            $creditMemo = null;
            $orderId = $orderData->getId();

            if ($orderData->getAction() == 'refund') {
                $creditMemo = $this->creditMemoRepository->get($orderId);
                $orderId = $creditMemo->getOrderId();
            }
            $order = $this->orderRepository->get($orderId);
            $externalId = $this->rallyConfig->getFormattedId("order", $order->getId(), $order->getCreatedAt());

            if ($orderData->getAction() == 'create') {
                $cart = $this->quoteRepository->get($order->getQuoteId());
                $cartId = $this->rallyConfig->getFormattedId('quote', (string) $cart->getId(), $cart->getCreatedAt());

                $url = 'webhooks/order-create';
                $orderData = [
                    'external_id' => $externalId,
                    'cart_id' => $cartId
                ];
            } else if ($orderData->getAction() == 'status') {
                $url = 'webhooks/order-status-update';
                $orderStatus = $this->cartMapper->getMappedStatus($order->getStatus());
                $orderData = [
                    "organization_id" => "",
                    "external_id" => $externalId,
                    "external_number" => $order->getIncrementId(),
                    "status" => $orderStatus
                ];
            } elseif ($orderData->getAction() == 'update') {
                $url = 'webhooks/order-update';
                $orderData = $this->getUpdateWebhookData($externalId, $order);
            } elseif ($orderData->getAction() == 'refund') {
                $url = 'webhooks/order-refund-create';
                $orderData = $this->getRefundWebhookData($externalId, $creditMemo);
            } else {
                return;
            }
            $this->hookManager->sendWebhookRequest($url, $orderData);
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * Get order update webhook data
     *
     * @param string $externalId
     * @param OrderInterface $order
     * @return array
     * @throws NoSuchEntityException|LocalizedException
     */
    private function getUpdateWebhookData(string $externalId, OrderInterface $order): array
    {
        $payment = $order->getPayment();
        $method = $payment->getMethodInstance();
        $methodCode = $method->getMethod();

        $quote = $this->quoteRepository->get($order->getQuoteId());
        $quoteId = $this->rallyConfig->getFormattedId("quote", (string)$quote->getId(), $quote->getCreatedAt());
        $orderStatus = $this->cartMapper->getMappedStatus($order->getStatus());
        $orderMapData = $this->cartMapper->getOrderData($order);
        $orderData = [
            "organization_id" => "",
            "external_id" => $externalId,
            "cart" => [
                "external_id" => $quoteId
            ],
            "email" => $order->getCustomerEmail(),
            "line_items" => [],
            "customer" => [],
            "billing_address" => [],
            "shipping_address" => [],
            "selected_shipping_line_external_id" => $order->getShippingMethod(),
            "discounts" => [],
            "external_number" => $order->getIncrementId(),
            "taxes" => [],
            "total_tax" => (float) $order->getTaxAmount(),
            "total_price" => (float) $order->getGrandTotal(),
            "subtotal_price" => (float) $order->getSubtotal(),
            "total_discounts" => abs((float) $order->getDiscountAmount()),
            "payment_method" => $methodCode,
            "status" => $orderStatus,
            "taxes_included_in_price" => false,
            "total_coupons_amount" => 0,
            "meta" => []
        ];
        return array_replace_recursive($orderData, $orderMapData);
    }

    /**
     * Get order refund webhook data
     *
     * @param string $externalId
     * @param CreditmemoInterface $creditMemo
     * @return array
     */
    private function getRefundWebhookData(string $externalId, CreditmemoInterface $creditMemo): array
    {
        $refundData = [
            "organization_id" => "",
            "external_id" => $creditMemo->getId(),
            "external_order_id" => $externalId,
            "amount" => (float) $creditMemo->getGrandTotal()
        ];

        $returnItems = [];
        foreach ($creditMemo->getAllItems() as $item) {
            if ($item->getOrderItem()->getParentItem()) {
                continue;
            }
            $refundItemData = [
                "external_id" => $item->getOrderItemId(),
                "type" => "line_item",
                "reason" => $creditMemo->getCustomerNote(),
                "quantity" => (int) $item->getQty(),
                "amount" => (float) $item->getRowTotal() - $item->getDiscountAmount()
            ];
            $returnItems[] = $refundItemData;
        }

        if ($creditMemo->getTaxAmount()) {
            $returnItems[] = $this->getRefundLines($creditMemo, 'tax', 'getTaxAmount');
        }
        if ($creditMemo->getShippingAmount()) {
            $returnItems[] = $this->getRefundLines($creditMemo, 'shipping_rate', 'getShippingAmount');
        }
        $refundData['refunds'] = $returnItems;
        return $refundData;
    }

    /**
     * Get order refund lines
     *
     * @param CreditmemoInterface $creditMemo
     * @param string $type
     * @param string $method
     * @return array
     */
    private function getRefundLines(CreditmemoInterface $creditMemo, string $type, string $method): array
    {
        return [
            "external_id" => $type . '-' . $creditMemo->getId(),
            "type" => $type,
            "reason" => $creditMemo->getCustomerNote(),
            "quantity" => 1,
            "amount" => (float) $creditMemo->$method()
        ];
    }
}
