<?php

declare(strict_types=1);

namespace Rally\Checkout\Model;

use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterfaceFactory;
use Magento\InventorySalesApi\Api\Data\SalesEventExtensionFactory;
use Magento\InventorySalesApi\Api\Data\SalesEventInterface;
use Magento\InventorySalesApi\Api\Data\SalesEventInterfaceFactory;
use Magento\InventorySalesApi\Api\PlaceReservationsForSalesEventInterface;
use Magento\InventorySalesApi\Model\StockByWebsiteIdResolverInterface;
use Magento\InventorySales\Model\CheckItemsQuantity;
use Magento\Store\Api\WebsiteRepositoryInterface;

/**
 *  Append Reservation after Order is placed
 */
class AppendReservations
{
    public function __construct(
        private PlaceReservationsForSalesEventInterface $placeReservationsForSalesEvent,
        private WebsiteRepositoryInterface $websiteRepository,
        private SalesChannelInterfaceFactory $salesChannelFactory,
        private SalesEventInterfaceFactory $salesEventFactory,
        private CheckItemsQuantity $checkItemsQuantity,
        private StockByWebsiteIdResolverInterface $stockByWebsiteIdResolver,
        private SalesEventExtensionFactory $salesEventExtensionFactory
    ) {
        $this->placeReservationsForSalesEvent = $placeReservationsForSalesEvent;
        $this->websiteRepository = $websiteRepository;
        $this->salesChannelFactory = $salesChannelFactory;
        $this->salesEventFactory = $salesEventFactory;
        $this->checkItemsQuantity = $checkItemsQuantity;
        $this->stockByWebsiteIdResolver = $stockByWebsiteIdResolver;
        $this->salesEventExtensionFactory = $salesEventExtensionFactory;
    }

    /**
     * Create reservations upon a sale event.
     *
     * @param int $websiteId
     * @param array $itemsBySku
     * @param mixed $order
     * @param array $itemsToSell
     * @return array
     * @throws \Exception
     */
    public function reserve($websiteId, $itemsBySku, $order, $itemsToSell)
    {
        $websiteCode = $this->websiteRepository->getById($websiteId)->getCode();
        $stockId = (int)$this->stockByWebsiteIdResolver->execute((int)$websiteId)->getStockId();

        $this->checkItemsQuantity->execute($itemsBySku, $stockId);

        /** @var SalesEventExtensionInterface */
        $salesEventExtension = $this->salesEventExtensionFactory->create([
            'data' => ['objectIncrementId' => (string)$order->getIncrementId()]
        ]);

        /** @var SalesEventInterface $salesEvent */
        $salesEvent = $this->salesEventFactory->create([
            'type' => SalesEventInterface::EVENT_ORDER_PLACED,
            'objectType' => SalesEventInterface::OBJECT_TYPE_ORDER,
            'objectId' => (string)$order->getEntityId()
        ]);
        $salesEvent->setExtensionAttributes($salesEventExtension);
        $salesChannel = $this->salesChannelFactory->create([
            'data' => [
                'type' => SalesChannelInterface::TYPE_WEBSITE,
                'code' => $websiteCode
            ]
        ]);

        $this->placeReservationsForSalesEvent->execute($itemsToSell, $salesChannel, $salesEvent);
        return [$salesChannel, $salesEventExtension];
    }
}
