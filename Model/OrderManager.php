<?php
declare(strict_types=1);

namespace Rally\Checkout\Model;

use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Sales\Api\Data\TransactionInterface;
use Rally\Checkout\Api\OrderManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Payment\Transaction;
use Magento\Sales\Api\Data\OrderInterface;
use Rally\Checkout\Api\Data\OrderDataInterface;
use Rally\Checkout\Api\Data\OrderDataInterfaceFactory;
use Rally\Checkout\Api\Service\RequestValidatorInterface;
use Rally\Checkout\Service\CartMapper;
use Magento\Quote\Model\QuoteManagement;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface;
use Rally\Checkout\Api\Data\PaymentDataInterfaceFactory;
use Magento\Sales\Api\OrderPaymentRepositoryInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Framework\DB\Transaction as InvoiceTransaction;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\Order;
use Rally\Checkout\Api\ConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Validator\Exception as ValidatorException;
use Magento\InventorySales\Model\AppendReservations;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\InventorySales\Model\ReservationExecutionInterface;
use Magento\InventorySalesApi\Api\Data\ItemToSellInterfaceFactory;
use Magento\InventoryCatalogApi\Model\GetSkusByProductIdsInterface;
use Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface;
use Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface;
use Rally\Checkout\Api\Data\PaymentDataInterface;

/**
 * Checkout OrderManager model.
 * @api
 */
class OrderManager implements OrderManagerInterface
{
    private const ERROR_PRODUCTS_OOS = "Some of the products are out of stock.";
    private const ERROR_PRODUCT_OOS = "This product is out of stock.";
    private const ERROR_INSUFFICIENT_STOCK = "The requested qty is not available";
    private const ERROR_NO_SOURCE_ITEM = "There are no source items with the in stock status";
    private const ERROR_NOT_SALABLE = "Product that you are trying to add is not available.";

    public function __construct(
        public OrderDataInterfaceFactory $orderDataFactory,
        public OrderRepositoryInterface $orderRepository,
        public Request $request,
        public CartMapper $cartMapper,
        public CartRepositoryInterface $quoteRepository,
        public RequestValidatorInterface $requestValidator,
        public QuoteManagement $quoteManagement,
        public BuilderInterface $transactionBuilder,
        public PaymentDataInterfaceFactory $paymentDataFactory,
        public OrderPaymentRepositoryInterface $orderPaymentRepository,
        public TransactionRepositoryInterface $transactionRepository,
        public ProductRepositoryInterface $productRepository,
        public ToOrderItem $quoteToOrder,
        public InvoiceService $invoiceService,
        public InvoiceTransaction $transaction,
        public InvoiceSender $invoiceSender,
        public Json $json,
        public EventManager $eventManager,
        public ConfigInterface $rallyConfig,
        public StoreManagerInterface $storeManager,
        public PriceCurrencyInterface $currencyConverter,
        public ReservationExecutionInterface $reservationExecution,
        public GetSkusByProductIdsInterface $getSkusByProductIds,
        public GetProductTypesBySkusInterface $getProductTypesBySkus,
        public IsSourceItemManagementAllowedForProductTypeInterface $sourceManagement,
        public ItemToSellInterfaceFactory $itemsToSellFactory,
        public AppendReservations $appendReservations
    ) {
    }

    /**
     * @inheritdoc
     */
    public function get(string $orgId, string $externalId): OrderDataInterface
    {
        $this->requestValidator->validate();
        $orderData = $this->orderDataFactory->create();
        $orderId = $this->rallyConfig->getId($externalId);
        $order = $this->orderRepository->get($orderId);

        $orderStatus = $this->cartMapper->getMappedStatus($order->getStatus());
        $orderData->setExternalId($externalId)
            ->setExternalNumber($order->getIncrementId())
            ->setStatus($orderStatus)
            ->setSubtotal((float) $order->getSubtotal())
            ->setTotal($order->getGrandTotal())
            ->setTaxAmount((float) $order->getTaxAmount());

        return $orderData;
    }

    /**
     * @inheritdoc
     */
    public function save(string $orgId, string $externalId): OrderDataInterface
    {
        $this->requestValidator->validate();

        $body = $this->request->getBodyParams();
        $orderId = $this->rallyConfig->getId($externalId);
        $order = $this->orderRepository->get($orderId);
        $orderState = $order->getState();

        if (isset($body['line_items']) && !in_array($orderState, [Order::STATE_PROCESSING, Order::STATE_COMPLETE])) {
            $itemPrice = $tax = $subtotal = $total = 0;
            $quote = $this->quoteRepository->get($order->getQuoteId());
            $this->storeManager->getStore()->setCurrentCurrencyCode($quote->getQuoteCurrencyCode());
            $newItems = $this->cartMapper->addProductsToQuote($body['line_items'], $quote, "ppo");
            $newItemIds = array_map(
                function ($item) {
                    return $item->getItemId();
                },
                $newItems
            );

            $itemsById = $itemsBySku = $itemsToSell = $ppoItems = [];
            $quoteItems = $quote->getAllItems();
            foreach ($quoteItems as $quoteItem) {
                if (!in_array($quoteItem->getItemId(), $newItemIds) &&
                    !in_array($quoteItem->getParentItemId(), $newItemIds)
                ) {
                    continue;
                }

                $orderItem = $this->quoteToOrder->convert($quoteItem);
                $orderItem->setIsPpo(true);
                $orderItemData = $order->getItemByQuoteItemId($quoteItem->getId());

                if ($orderItemData) {
                    $orderItemData->addData($orderItem->getData());
                } else {
                    if ($quoteItem->getParentItem()) {
                        $orderItem->setParentItem(
                            $order->getItemByQuoteItemId($quoteItem->getParentItem()->getId())
                        );
                    } else {
                        $itemPrice += $quoteItem->getRowTotal();
                    }
                    $order->addItem($orderItem);
                }

                if (!isset($itemsById[$orderItem->getProductId()])) {
                    $itemsById[$orderItem->getProductId()] = 0;
                }
                $ppoItems[] = $orderItem;
                $itemsById[$orderItem->getProductId()] += $orderItem->getQtyOrdered();
            }
            $productSkus = $this->getSkusByProductIds->execute(array_keys($itemsById));
            $productTypes = $this->getProductTypesBySkus->execute($productSkus);

            foreach ($productSkus as $productId => $sku) {
                if (false === $this->sourceManagement->execute($productTypes[$sku])) {
                    continue;
                }

                $itemsBySku[$sku] = (float)$itemsById[$productId];
                $itemsToSell[] = $this->itemsToSellFactory->create([
                    'sku' => $sku,
                    'qty' => -(float)$itemsById[$productId]
                ]);
            }

            $websiteId = (int)$order->getStore()->getWebsiteId();
            $this->appendReservations->reserve($websiteId, $itemsBySku, $order, $itemsToSell);
            $this->orderRepository->save($order);
            $this->eventManager->dispatch('ppo_order_update_after', ['ppo_items' => $ppoItems]);
            if (isset($body['shipping_costs']['new']) && $body['shipping_costs']['new']['total'] > 0) {
                $shippingCosts = [];
                $newShippings = $body['shipping_costs']['new'];
                $tax = $newShippings['tax_amount'];
                $subtotal = $newShippings['subtotal'];
                $total = $newShippings['total'];
                $quoteShipping = $quote->getShippingCosts();

                if ($quoteShipping) {
                    $shippingCosts = $this->json->unserialize($quoteShipping);
                }
                $quoteItem = current($newItems);
                $orderItem = $order->getItemByQuoteItemId($quoteItem->getId());
                $shippingCosts[$orderItem->getItemId()] = $newShippings;
                $quote->setShippingCosts($this->json->serialize($shippingCosts));
            }
            $this->quoteRepository->save($quote->setTotalsCollectedFlag(true));

            $order->setShippingCosts($quote->getShippingCosts());
            $order->setGrandTotal($order->getGrandTotal() + $itemPrice + $total);
            $order->setShippingAmount($order->getShippingAmount() + $subtotal);
            $order->setShippingTaxAmount($order->getShippingTaxAmount() + $tax);
            $order->setSubtotal($order->getSubtotal() + $itemPrice);
            $order->setTaxAmount($order->getTaxAmount() + $tax);
            $order->setTotalQtyOrdered($quote->getItemsQty());
            $order->setSubtotalInclTax($order->getSubtotalInclTax() + $itemPrice);
            $order->setShippingInclTax($order->getShippingInclTax() + $total);

            $baseCurrencyCode = $order->getBaseCurrencyCode();
            $orderCurrencyCode = $order->getOrderCurrencyCode();

            if ($baseCurrencyCode != $orderCurrencyCode) {
                $currency = $this->currencyConverter->getCurrency($order->getStore(), $orderCurrencyCode);
                $store = $order->getStore()->setBaseCurrency($currency);
                $itemPrice = $this->currencyConverter->convert($itemPrice, $store, $baseCurrencyCode);
                $total = $this->currencyConverter->convert($total, $store, $baseCurrencyCode);
                $subtotal = $this->currencyConverter->convert($subtotal, $store, $baseCurrencyCode);
                $tax = $this->currencyConverter->convert($tax, $store, $baseCurrencyCode);
            }

            $order->setBaseShippingInclTax($order->getBaseShippingInclTax() + $total);
            $order->setBaseSubtotal($order->getBaseSubtotal() + $itemPrice);
            $order->setBaseTaxAmount($order->getBaseTaxAmount() + $tax);
            $order->setBaseSubtotalInclTax($order->getBaseSubtotalTotalInclTax() + $itemPrice);
            $order->setBaseShippingAmount($order->getBaseShippingAmount() + $subtotal);
            $order->setBaseGrandTotal($order->getBaseGrandTotal() + $itemPrice + $total);
            $order->addCommentToStatusHistory(
                __('%1 item(s) added in Order by Rally PPO.', count($newItems))
            );

            $this->orderRepository->save($order);
        }

        if (!empty($body['transactions'])) {
            $paymentData = $this->paymentDataFactory->create();
            $this->createTransaction($order, $body['transactions'], $paymentData);
        }

        $isFraud  = !empty($body['fraud_review_transaction']);
        if (isset($body['status']) && isset($body['payment_method']) && !$isFraud) {
            $payment = $order->getPayment();
            $payment->setAdditionalInformation(
                [Transaction::RAW_DETAILS => (array) $body['payment_method']]
            );
            $this->orderPaymentRepository->save($payment);

            if (strtolower($body['status']) == 'paid' && $order->canInvoice()) {
                $invoice = $this->invoiceService->prepareInvoice($order);
                $invoice->register();
                $invoice->save();

                $transactionSave = $this->transaction->addObject(
                    $invoice
                )->addObject(
                    $invoice->getOrder()
                );
                $transactionSave->save();
                $this->invoiceSender->send($invoice);
                $order->addCommentToStatusHistory(
                    __('Notified customer about invoice #%1.', $invoice->getId())
                )->setIsCustomerNotified(true);

                $status = $order->hasShipments() ? Order::STATE_COMPLETE : Order::STATE_PROCESSING;
                $order->setState($status)->setStatus($status);
                $this->orderRepository->save($order);
            }
        } elseif ($isFraud) {
            $review = is_array($body['fraud_review_transaction']) ?
                $this->json->serialize($body['fraud_review_transaction']) : null;
            $order->setReviewTransaction($review);
            $order->setState(Order::STATE_PENDING_PAYMENT)->setStatus(Order::STATUS_FRAUD);
            $this->orderRepository->save($order);
        }

        return $this->get($orgId, $externalId);
    }

    /**
     * @inheritdoc
     */
    public function placeOrder(string $orgId): OrderDataInterface
    {
        $this->requestValidator->validate();
        $orderData = $this->orderDataFactory->create();
        $rallyOrderData = $this->request->getBodyParams();

        if (isset($rallyOrderData['cart']['external_id'])) {
            $externalId = $rallyOrderData['cart']['external_id'];
            $quoteId = $this->rallyConfig->getId($externalId);
            $quote = $this->quoteRepository->get($quoteId);
            $currentStore = $this->storeManager->getStore();
            if ($currentStore->getWebsite() != $quote->getStore()->getWebsite()) {
                $this->requestValidator->handleException('cart_not_found');
            } elseif ($currentStore->getId() != $quote->getStoreId()) {
                $this->storeManager->setCurrentStore($quote->getStore());
                $this->storeManager->getStore()->setCurrentCurrencyCode($quote->getQuoteCurrencyCode());
            } else {
                $currentStore->setCurrentCurrencyCode($quote->getQuoteCurrencyCode());
            }

            $itemCount = $quote->getItemsCount();
            $itemQtys = $quote->getItemsQty();
            $rallyItemCount = count($rallyOrderData["line_items"]);
            $rallyItemQtys = array_sum(
                array_column($rallyOrderData["line_items"], 'quantity')
            );

            if ($itemCount != $rallyItemCount || $itemQtys != $rallyItemQtys) {
                $this->requestValidator->handleException('cart_has_been_updated');
            }

            $customerEmail = $rallyOrderData['customer']['email'];
            $customerFirstName = $rallyOrderData['customer']['first_name'];
            $customerLastName = $rallyOrderData['customer']['last_name'];

            $quote->setCustomerEmail($customerEmail)
                ->setCustomerFirstname($customerFirstName)
                ->setCustomerLastname($customerLastName);

            $this->cartMapper->updateCartsData($orgId, $externalId, $rallyOrderData);

            if (isset($rallyOrderData['payment_method'])) {
                $quote->setPaymentMethod('rallypayment');
                $quote->getPayment()->importData(
                    [
                        'method' => 'rallypayment',
                        'additional_data' => $rallyOrderData['payment_method']
                    ]
                );

                try {
                    $quote->getShippingAddress()->setAppliedRuleIds('');
                    $quote->setAppliedRuleIds('');
                    $this->quoteRepository->save($quote->collectTotals());
                    $orderId = $this->quoteManagement->placeOrder($quote->getId());
                } catch (ValidatorException|\Exception $e) {
                    $errors = [
                        self::ERROR_PRODUCTS_OOS,
                        self::ERROR_PRODUCT_OOS,
                        self::ERROR_INSUFFICIENT_STOCK,
                        self::ERROR_NO_SOURCE_ITEM,
                        self::ERROR_NOT_SALABLE
                    ];

                    $errorCode = "unknown";
                    $errorMessage = $e->getMessage();
                    if (in_array($errorMessage, $errors)) {
                        $this->requestValidator->handleOrderException('out_of_stock', $errorMessage);
                    }
                    if (strpos($errorMessage, 'regionId') !== false) {
                        $errorCode = "cart_has_been_updated";
                        $errorMessage = "Looks like a state is required for the country selected. " .
                            "Please adjust the setting in your Magento admin to proceed.";
                    }
                    $this->requestValidator->handleOrderException($errorCode, $errorMessage);
                }

                if ($orderId) {
                    $order = $this->orderRepository->get($orderId);
                    return $this->processOrder($orgId, $order, $rallyOrderData);
                }
            }
        }
        return $orderData;
    }

    /**
     * Process rally order
     *
     * @param string $orgId
     * @param OrderInterface $order
     * @param array $rallyOrderData
     * @return OrderDataInterface
     * @throws WebapiException
     */
    private function processOrder(string $orgId, OrderInterface $order, array $rallyOrderData): OrderDataInterface
    {
        $paymentData = $this->paymentDataFactory->create();
        if (!empty($rallyOrderData['transactions'])) {
            $paymentData = $this->createTransaction($order, $rallyOrderData['transactions'], $paymentData);
        }

        if (isset($rallyOrderData['meta']['autoCreateCustomers']) &&
            $rallyOrderData['meta']['autoCreateCustomers']
        ) {
            $this->eventManager->dispatch('rally_create_guest_account', ['order' => $order]);
        }

        if (!empty($rallyOrderData['fraud_review_transaction'])) {
            $review = is_array($rallyOrderData['fraud_review_transaction']) ?
                $this->json->serialize($rallyOrderData['fraud_review_transaction']) : null;
            $order->setReviewTransaction($review);
            $order->setState(Order::STATE_PENDING_PAYMENT)->setStatus(Order::STATUS_FRAUD);
            $this->orderRepository->save($order);
        }

        if (!empty($rallyOrderData['device_information']['device_ip_address'])) {
            $order->setRemoteIp($rallyOrderData['device_information']['device_ip_address'])->save();
        }
        $externalId = $this->rallyConfig->getFormattedId("order", $order->getId(), $order->getCreatedAt());
        $orderData = $this->get($orgId, $externalId);
        $orderData->setPaymentCreated($paymentData);
        return $orderData;
    }

    /**
     * Create rally order transaction
     *
     * @param OrderInterface $order
     * @param array $rallyTransactions
     * @param PaymentDataInterface $paymentData
     * @return PaymentDataInterface
     */
    private function createTransaction($order, $rallyTransactions, $paymentData)
    {
        $payment = $order->getPayment();
        $txnId = $txnAmount = 0;
        $orderId = $order->getId();
        $paymentId = $payment->getEntityId();

        foreach ($rallyTransactions as $rallyTransaction) {
            $externalId = $rallyTransaction['external_id'];
            $transaction = $this->transactionRepository->getByTransactionId($externalId, $paymentId, $orderId);
            if ($transaction && $transaction->getId()) {
                continue;
            }

            $payment->setLastTransId($externalId);
            $payment->setTransactionId($externalId);
            $payment->setAdditionalInformation(
                [Transaction::RAW_DETAILS => (array) $rallyTransaction]
            );
            $txnAmount = $rallyTransaction['amount'];
            $formattedPrice = $order->getBaseCurrency()->formatTxt($txnAmount);

            $message = __('The authorized amount is %1.', $formattedPrice);
            $transaction = $this->transactionBuilder->setPayment($payment)
                ->setOrder($order)
                ->setTransactionId($externalId)
                ->setAdditionalInformation(
                    [Transaction::RAW_DETAILS => (array) $rallyTransaction]
                )
                ->setFailSafe(true)
                ->build(TransactionInterface::TYPE_CAPTURE);

            $payment->addTransactionCommentsToOrder(
                $transaction,
                $message
            );
            $payment->setParentTransactionId(null);
            $this->orderPaymentRepository->save($payment);
            $order->save();
            $txnId = $this->transactionRepository->save($transaction)->getTransactionId();
        }
        return $paymentData
            ->setExternalPlatformTransactionId($txnId)
            ->setAmount($txnAmount);
    }
}
