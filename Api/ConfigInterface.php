<?php

namespace Rally\Checkout\Api;

/**
 * Interface ConfigInterface
 * @api
 */
interface ConfigInterface
{
    public const XML_PATH_IS_ENABLED = 'rally_checkout/general/enabled';
    public const XML_PATH_IS_SANDBOX = 'rally_checkout/general/sandbox';
    public const XML_PATH_IS_LOAD_SDK = 'rally_checkout/general/load_sdk';
    public const XML_PATH_API_KEY = 'rally_checkout/general/api_key';
    public const XML_PATH_CLIENT_ID = 'rally_checkout/general/client_id';
    public const RALLY_SANDBOX_ENDPOINT = "https://api.sandbox.onrally.com/connector-api/v1/";
    public const RALLY_PRODUCTION_ENDPOINT = "https://api.onrally.com/connector-api/v1/";
    public const RALLY_SANDBOX_SDK = "https://js.sandbox.onrally.com/resources/platforms/magento.js";
    public const RALLY_PRODUCTION_SDK = "https://js.onrally.com/resources/platforms/magento.js";

    /**
     * Get Is Rally Checkout Enabled
     *
     * @param string|null $scope
     * @param int|null $scopeId
     * @return bool
     */
    public function isEnabled(string $scope = null, int $scopeId = null): bool;

    /**
     * Get Is Sandbox mode
     *
     * @param int|null $store
     * @return bool
     */
    public function isSandboxMode(int $store = null): bool;

    /**
     * Get Is load SDK
     *
     * @param int|null $store
     * @return bool
     */
    public function isLoadSdk(int $store = null): bool;

    /**
     * Get Rally Endpoint URL
     *
     * @param int|null $store
     * @return string
     */
    public function getRallyUrl(int $store = null): string;

    /**
     * Get Rally SDK URL
     *
     * @param int|null $store
     * @return string
     */
    public function getSdkUrl(int $store = null): string;

    /**
     * Get Rally Checkout API Key
     *
     * @param int|null $store
     * @return string
     */
    public function getApiKey(int $store = null): string;

    /**
     * Get Is Rally Checkout Enabled
     *
     * @param int|null $store
     * @return string
     */
    public function getClientId(int $store = null): string;

    /**
     * Get formatted quote/order Id
     *
     * @param string $type
     * @param string $id
     * @param string $createdTime
     * @return string
     */
    public function getFormattedId(string $type, string $id, string $createdTime): string;

    /**
     * Get quote/order ID
     *
     * @param string $formattedId
     * @return string
     */
    public function getId(string $formattedId): string;
}
