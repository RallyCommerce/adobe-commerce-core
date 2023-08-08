<?php

namespace Rally\Checkout\Api;

use Magento\Framework\Webapi\Exception;
use Rally\Checkout\Api\Data\StoreConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface StoreConfigManagerInterface
 * @api
 */
interface StoreConfigManagerInterface
{
    /**
     * GET Store Configurations
     *
     * @param string $orgId
     * @return StoreConfigInterface
     * @throws Exception|NoSuchEntityException
     */
    public function get(string $orgId): StoreConfigInterface;
}
