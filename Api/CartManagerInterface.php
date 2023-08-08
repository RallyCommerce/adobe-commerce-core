<?php

namespace Rally\Checkout\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Exception;
use Rally\Checkout\Api\Data\CartDataInterface;

/**
 * Interface CartManagerInterface
 * @api
 */
interface CartManagerInterface
{
    /**
     * Create new cart
     *
     * @param string $orgId
     * @return CartDataInterface
     * @throws Exception The specified data could not correct.
     * @throws NoSuchEntityException The specified product does not exist.
     * @throws LocalizedException
     */
    public function create(string $orgId): CartDataInterface;

    /**
     * GET cart data
     *
     * @param string $orgId
     * @param string $externalId
     * @return CartDataInterface
     * @throws NoSuchEntityException The specified cart does not exist.
     * @throws Exception The specified data could not correct.
     * @throws LocalizedException
     */
    public function get(string $orgId, string $externalId): CartDataInterface;

    /**
     * Update the specified cart.
     *
     * @param string $orgId
     * @param string $externalId
     * @return CartDataInterface
     * @throws Exception
     * @throws NoSuchEntityException The specified cart does not exist.
     * @throws CouldNotSaveException The specified data could not be saved to the cart.
     * @throws InputException The specified cart is not valid.
     * @throws LocalizedException
     */
    public function save(string $orgId, string $externalId): CartDataInterface;

    /**
     * Add Items in specified cart
     *
     * @param string $orgId
     * @param string $externalId
     * @return CartDataInterface
     * @throws NoSuchEntityException The specified cart does not exist.
     * @throws Exception The specified data could not correct.
     * @throws LocalizedException
     */
    public function addItems(string $orgId, string $externalId): CartDataInterface;

    /**
     * Remove Items from specified cart
     *
     * @param string $orgId
     * @param string $externalId
     * @return CartDataInterface
     * @throws NoSuchEntityException The specified cart does not exist.
     * @throws Exception The specified data could not correct.
     * @throws LocalizedException
     */
    public function removeItems(string $orgId, string $externalId): CartDataInterface;
}
