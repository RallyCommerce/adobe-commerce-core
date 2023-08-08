<?php

namespace Rally\Checkout\Api\Service;

use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface HookManagerInterface
 */
interface HookManagerInterface
{
    /**
     * Register/Create all hooks
     *
     * @return int
     * @throws NoSuchEntityException
     */
    public function createHooks(): int;

    /**
     * Get all registered hooks
     *
     * @return int
     * @throws GuzzleException
     */
    public function checkAndCreateHooks(): int;

    /**
     * Set config scope
     *
     * @param string $websiteId
     * @param string $storeId
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function setConfigScope(string $websiteId, string $storeId): void;

    /**
     * Get total hooks count
     *
     * @return int|null
     */
    public function getHooksCount(): ?int;

    /**
     * Send store info webhook
     *
     * @return void
     */
    public function sendStoreInfo(): void;

    /**
     * Send order webhooks
     *
     * @param string $url
     * @param array $webhookData
     * @return void
     */
    public function sendWebhookRequest(string $url, array $webhookData): void;
}
