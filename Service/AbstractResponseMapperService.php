<?php

namespace Rally\Checkout\Service;

use Rally\Checkout\Api\Service\ResponseMapperServiceInterface;

abstract class AbstractResponseMapperService implements ResponseMapperServiceInterface
{
    /**
     * Fields to map
     */
    protected array $mapFields = [];

    /**
     * @inheritdoc
     */
    public function map($dataObject, $responseData)
    {
        foreach ($this->mapFields as $metaName => $methodName) {
            if (array_key_exists($metaName, $responseData)) {
                $dataObject->$methodName($responseData[$metaName]);
            }

            if ($metaName === 'address') {
                $streetData = [
                    $responseData['address1'] ?? "",
                    $responseData['address2'] ?? ""
                ];
                $dataObject->$methodName($streetData);
            }
        }

        return $dataObject;
    }
}
