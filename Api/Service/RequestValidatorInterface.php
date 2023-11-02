<?php

namespace Rally\Checkout\Api\Service;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
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

    /**
     * Handle multi store and currency
     *
     * @param CartInterface $quote
     * @return void
     * @throws WebapiException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function handleMultiStoreCurrency(CartInterface $quote): void;
}
