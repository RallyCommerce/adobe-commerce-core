<?php

namespace Rally\Checkout\Api\Service;

use Magento\Framework\Webapi\Exception as WebapiException;

/**
 * Interface RequestValidatorInterface
 */
interface RequestValidatorInterface
{
    /**
     * Validate request authenticity
     *
     * @return void
     * @throws WebapiException
     */
    public function validate(): void;

    /**
     * Handle not exist exception
     *
     * @param string $code
     * @param array $details
     * @return void
     * @throws WebapiException
     */
    public function handleException(string $code, array $details = []): void;

    /**
     * Handle order exception
     *
     * @param string $code
     * @param string $message
     * @return void
     * @throws WebapiException
     */
    public function handleOrderException(string $code, string $message): void;
}
