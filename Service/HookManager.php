<?php
declare(strict_types=1);

namespace Rally\Checkout\Service;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use Magento\Framework\App\ScopeInterface;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Directory\Model\RegionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Rally\Checkout\Api\Service\HookManagerInterface;
use Rally\Checkout\Api\Data\HooksDataInterfaceFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Rally\Checkout\Api\Gateway\RequestBuilderInterface;
use Magento\Store\Model\ScopeInterface as StoreScopeInterface;

/**
 * HookManager Service
 * @api
 */
class HookManager implements HookManagerInterface
{
    public array $magentoHooks = [
        "cart/create" => ["post","rest/V1/merchants/:orgId/carts"],
        "cart/fetch" => ["post","rest/V1/merchants/:orgId/carts/:resourceId"],
        "cart/update" => ["put","rest/V1/merchants/:orgId/carts/:resourceId"],
        "cart/add" => ["post","rest/V1/merchants/:orgId/carts/:resourceId/add"],
        "cart/remove" => ["post","rest/V1/merchants/:orgId/carts/:resourceId/remove"],
        "order/create" => ["post","rest/V1/merchants/:orgId/orders"],
        "order/update" => ["put","rest/V1/merchants/:orgId/orders/:resourceId"],
        "order/fetch" => ["get","rest/V1/merchants/:orgId/orders/:resourceId"],
        "products/fetch" => ["post","rest/V1/merchants/:orgId/products/search"],
        "categories/fetch" => ["post","rest/V1/merchants/:orgId/categories/search"],
        "shipping_zones/fetch" => ["get","rest/V1/merchants/:orgId/shipping-zones"],
        "store_information/fetch" => ["get","rest/V1/merchants/:orgId/store"]
    ];

    protected array $addressConfigPaths = [
        'street_line1',
        'street_line2',
        'city',
        'region_id',
        'postcode'
    ];

    private $client;
    private $currentStore;
    private $currentConfigScope;

    public function __construct(
        private readonly HooksDataInterfaceFactory $hooksDataFactory,
        private readonly StoreManagerInterface $storeManager,
        private readonly RequestBuilderInterface $requestBuilder,
        private readonly ClientFactory $clientFactory,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly RegionFactory $regionFactory,
        private readonly Json $json
    ) {
    }

    /**
     * @inheritdoc
     */
    public function createHooks(): int
    {
        $hookCount = 0;
        $baseApiUrl = $this->currentStore->getBaseUrl();

        foreach ($this->magentoHooks as $type => $url) {
            $hooksData = $this->hooksDataFactory->create();
            $hooksData->setType($type)
                ->setUrl($baseApiUrl . $url[1]);

            $requestBody = $hooksData->getData();
            $requestBody['method'] = $url[0];
            $requestBody = $this->json->serialize($requestBody);
            $request = $this->requestBuilder->setUrl("hooks")->build("POST", $requestBody);

            try {
                $response = $this->getClient()->send($request);
                $response = $this->json->unserialize($response->getBody()->getContents());
                $contents = $response['data'];
            } catch (\Exception|GuzzleException $e) {
                $contents = [];
            }
            $hookCount = empty($contents) ? $hookCount : $hookCount+1;
        }
        return $hookCount;
    }

    /**
     * @inheritdoc
     */
    public function checkAndCreateHooks(): int
    {
        $hookCount = 0;
        $requestBody = $this->json->serialize([
            "method" => "get"
        ]);
        $request = $this->requestBuilder->setUrl("hooks")->build("GET", $requestBody);

        try {
            $response = $this->getClient()->send($request);
            $response = $this->json->unserialize($response->getBody()->getContents());
            $contents = $response['data'];

            if (count($contents) > 0) {
                foreach ($contents as $hook) {
                    $request = $this->requestBuilder->setUrl("hooks/".$hook['id'])->build("DELETE", $requestBody);
                    $this->getClient()->send($request);
                }
            }
            $hookCount = $this->createHooks();
        } catch (\Exception $e) {
        }
        return $hookCount;
    }

    /**
     * @inheritdoc
     */
    public function setConfigScope(string $websiteId, string $storeId): void
    {
        if ($storeId) {
            $scopeType = StoreScopeInterface::SCOPE_STORES;
        } elseif ($websiteId) {
            $scopeType = StoreScopeInterface::SCOPE_WEBSITES;
            $storeId = $this->storeManager->getWebsite($websiteId)->getDefaultStore()->getId();
        } else {
            $scopeType = ScopeInterface::SCOPE_DEFAULT;
            $storeId = $this->storeManager->getDefaultStoreView()->getStoreId();
        }
        $this->currentConfigScope = $scopeType;
        $this->currentStore = $this->storeManager->getStore($storeId);
    }

    /**
     * @inheritdoc
     */
    public function getHooksCount(): ?int
    {
        return count($this->magentoHooks);
    }

    /**
     * @inheritdoc
     */
    public function sendStoreInfo(): void
    {
        $storeId = $this->currentStore->getId();
        $country = $this->getConfig('country/default');
        $weightUnit = $this->getConfig('locale/weight_unit');
        $address = '';
        foreach ($this->addressConfigPaths as $addressConfigPath) {
            $value = $this->getConfig('store_information/' . $addressConfigPath);

            if ($addressConfigPath == 'region_id' && $value) {
                $value = $this->regionFactory->create()->load($value)->getName() . ',';
            }
            $address = $value ? $address . " " . $value : $address;
        }

        $storeInfo = [
            "external_id" => $storeId,
            "organization_id" => "",
            "country_code" => $this->getConfig('store_information/country_id') ?: $country,
            "address" => trim((string) $address ? $address : $country),
            "phone" => $this->getConfig('store_information/phone'),
            "locale" => $this->getConfig('locale/code'),
            "timezone" => $this->getConfig('locale/timezone'),
            "currency" => $this->currentStore->getCurrentCurrencyCode(),
            "weight_unit" => $weightUnit == 'kgs' ? 'kg' : $weightUnit
        ];
        $this->sendWebhookRequest("webhooks/shop-info-update", $storeInfo);
    }

    /**
     * @inheritdoc
     */
    public function sendWebhookRequest(string $url, array $webhookData): void
    {
        $requestBody = $this->json->serialize($webhookData);
        $request = $this->requestBuilder->setUrl($url)->build("POST", $requestBody);

        try {
            $this->getClient()->send($request);
        } catch (\Exception|GuzzleException $e) {
        }
    }

    /**
     * Get config value
     *
     * @param string $configPath
     * @return mixed
     */
    private function getConfig(string $configPath): mixed
    {
        $storeId = $this->currentStore->getId();
        return $this->scopeConfig->getValue(
            'general/'.$configPath,
            StoreScopeInterface::SCOPE_STORES,
            $storeId
        );
    }

    /**
     * Get API client
     *
     * @return Client
     */
    private function getClient(): Client
    {
        if ($this->client instanceof Client) {
            return $this->client;
        }

        $this->client = $this->clientFactory->create();
        return $this->client;
    }
}
