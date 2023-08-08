<?php

namespace Rally\Checkout\Api;

use Magento\Framework\Webapi\Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Rally\Checkout\Api\Data\ShippingZonesDataInterface;

/**
 * Interface ShippingZonesManagerInterface
 * @api
 */
interface ShippingZonesManagerInterface
{
    /**
     * GET Shipping Zones
     *
     * @param string $orgId
     * @return ShippingZonesDataInterface
     * @throws Exception
     * @throws NoSuchEntityException
     */
    public function get(string $orgId);
}
