<?php

namespace Rally\Checkout\Api;

use Magento\Framework\Webapi\Exception;
use Rally\Checkout\Api\Data\OrderDataInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Interface OrderManagerInterface
 * @api
 */
interface OrderManagerInterface
{
    /**
     * GET order data
     *
     * @param string $orgId
     * @param string $externalId
     * @return OrderDataInterface
     * @throws Exception
     */
    public function get(string $orgId, string $externalId): OrderDataInterface;

    /**
     * Update the specified order.
     *
     * @param string $orgId
     * @param string $externalId
     * @return OrderDataInterface $orderData
     * @throws Exception
     * @throws LocalizedException
     * @throws NoSuchEntityException The specified order does not exist.
     * @throws CouldNotSaveException The specified data could not be saved to the order.
     * @throws InputException The specified order is not valid.
     */
    public function save(string $orgId, string $externalId): OrderDataInterface;

    /**
     * Place an order for a specified cart.
     *
     * @param string $orgId
     * @return OrderDataInterface
     * @throws Exception
     * @throws LocalizedException
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function placeOrder(string $orgId): OrderDataInterface;
}
