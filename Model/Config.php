<?php
declare(strict_types=1);

namespace Rally\Checkout\Model;

use Magento\Store\Model\ScopeInterface;
use Rally\Checkout\Api\ConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Checkout config model.
 * @api
 */
class Config implements ConfigInterface
{
    public function __construct(
        public EncryptorInterface $encryptor,
        public ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @inheritdoc
     */
    public function isEnabled(string $scope = null, int $scopeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_IS_ENABLED,
            $scope ?? ScopeInterface::SCOPE_STORE,
            $scopeId
        );
    }

    /**
     * @inheritdoc
     */
    public function isSandboxMode(int $store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_IS_SANDBOX,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @inheritdoc
     */
    public function isLoadSdk(int $store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_IS_LOAD_SDK,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @inheritdoc
     */
    public function getRallyUrl(int $store = null): string
    {
        $isSandboxMode = $this->isSandboxMode();
        return $isSandboxMode ? self::RALLY_SANDBOX_ENDPOINT : self::RALLY_PRODUCTION_ENDPOINT;
    }

    /**
     * @inheritdoc
     */
    public function getSdkUrl(int $store = null): string
    {
        $isSandboxMode = $this->isSandboxMode();
        return $isSandboxMode ? self::RALLY_SANDBOX_SDK : self::RALLY_PRODUCTION_SDK;
    }

    /**
     * @inheritdoc
     */
    public function getApiKey(int $store = null): string
    {
        $apiKey = (string) $this->scopeConfig->getValue(
            self::XML_PATH_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $store
        );
        return $this->encryptor->decrypt($apiKey);
    }

    /**
     * @inheritdoc
     */
    public function getClientId(int $store = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_CLIENT_ID,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @inheritdoc
     */
    public function getFormattedId(string $type, string $id, string $createdTime): string
    {
        return $type . "_" . $id . "_ts_" . strtotime($createdTime);
    }

    /**
     * @inheritdoc
     */
    public function getId(string $formattedId): string
    {
        $ids = explode("_", $formattedId);
        return $ids[1] ?? "";
    }
}
