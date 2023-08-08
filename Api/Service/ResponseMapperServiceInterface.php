<?php

namespace Rally\Checkout\Api\Service;

/**
 * Interface ResponseMapperServiceInterface
 *
 * This interface is to be used by classes which map data
 */
interface ResponseMapperServiceInterface
{
    /**
     * Map data
     *
     * @param $dataObject
     * @param array $responseData
     */
    public function map($dataObject, array $responseData);
}
