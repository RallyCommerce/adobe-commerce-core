<?php

namespace Rally\Checkout\Plugin;

use Magento\Framework\Webapi\ServiceOutputProcessor;

class ServiceOutputProcessorPlugin
{
    public const SHIPPING_ZONE_CLASS = '\Rally\Checkout\Api\Data\ShippingZonesDataInterface';

    /**
     * Convert associative array into proper data object.
     *
     * @param ServiceOutputProcessor $subject
     * @param mixed $result
     * @param mixed $data
     * @param string $type
     * @return mixed
     */
    public function afterConvertValue(
        ServiceOutputProcessor $subject,
        mixed $result,
        mixed $data,
        string $type
    ): mixed {
        if ($type == self::SHIPPING_ZONE_CLASS && is_array($data)) {
            $result = [];
            foreach ($data as $key => $datum) {
                $result[$key] = $datum;
            }
        }
        return $result;
    }
}
