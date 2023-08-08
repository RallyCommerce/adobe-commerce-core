<?php

namespace Rally\Checkout\Api;

use Rally\Checkout\Api\Data\RegisterDataInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Exception;

/**
 * Interface RegisterManagerInterface
 * @api
 */
interface RegisterManagerInterface
{
    /**
     * GET connector installation/register callback
     *
     * @param string $orgId
     * @return RegisterDataInterface
     * @throws Exception
     * @throws NoSuchEntityException
     */
    public function get(string $orgId): RegisterDataInterface;
}
