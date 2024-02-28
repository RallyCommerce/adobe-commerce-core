<?php

namespace Rally\Checkout\Observer;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rally\Checkout\Api\ConfigInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Rally\Checkout\Api\Service\HookManagerInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\HTTP\Client\CurlFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

/**
 * Rally config save observer
 */
class RallyConfigSaveObserver implements ObserverInterface
{
    /**
     * Successful result code.
     */
    private const HTTP_OK = 200;

    /**
     * Localhost IP address.
     */
    private const LOCALHOST_IP = '127.0.0.1';

    public function __construct(
        private readonly ConfigInterface $rallyConfig,
        private readonly HookManagerInterface $hookManager,
        private readonly MessageManagerInterface $messageManager,
        private readonly RequestInterface $request,
        private readonly StoreManagerInterface $storeManager,
        private readonly CurlFactory $curlFactory,
        private readonly RemoteAddress $remoteAddress
    ) {
    }

    /**
     * Rally register hooks
     *
     * @param EventObserver $observer
     * @return void
     * @throws GuzzleException
     * @throws NoSuchEntityException
     */
    public function execute(EventObserver $observer): void
    {
        $eventData = $observer->getEvent();
        $changedPaths = (array) $eventData->getChangedPaths();

        if ($eventData->getStore()) {
            $scope = ScopeInterface::SCOPE_STORES;
            $scopeId = $eventData->getStore();
        } elseif ($eventData->getWebsite()) {
            $scope = ScopeInterface::SCOPE_WEBSITES;
            $scopeId = $eventData->getWebsite();
        } else {
            $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
            $scopeId = null;
        }

        if (!empty($changedPaths) && $this->rallyConfig->isEnabled($scope, $scopeId)) {

            if (!$this->checkIsValidRequest($eventData)) {
                $this->messageManager->addWarningMessage(
                    __('The URL of your Magento instance is inaccessible to external requests.
                        Please register your Rally Checkout to an externally accessible instance URL.')
                );

                return;
            }

            $this->hookManager->setConfigScope($eventData->getWebsite(), $eventData->getStore());
            $magentoHooksCount = $this->hookManager->getHooksCount();
            $registeredHooks = $this->hookManager->checkAndCreateHooks();

            if ($magentoHooksCount == $registeredHooks) {
                $this->messageManager->addSuccessMessage(__('%1 Rally hook(s) created Successfully.', $registeredHooks));
            } else {
                $this->messageManager->addWarningMessage(__('Issue while Creating Rally hook(s).'));
            }
        }
    }

    private function checkIsValidRequest($eventData): bool
    {
        $result = true;
        $requestIp = $this->request->getClientIp();
        $serverIp = $this->request->get('SERVER_ADDR');
        $httpHost = $this->request->getHttpHost();

        if ($requestIp == self::LOCALHOST_IP || $serverIp == self::LOCALHOST_IP) {
            $result = false;
        } else {
            $websiteId = $eventData->getWebsite();
            $storeId = $eventData->getStore();

            if (!$storeId && $websiteId) {
                $storeId = $this->storeManager->getWebsite($websiteId)->getDefaultStore()->getId();
            } else if (!$storeId && !$websiteId) {
                $storeId = $this->storeManager->getDefaultStoreView()->getStoreId();
            }
            $store = $this->storeManager->getStore($storeId);
            $baseUrl = $store->getBaseUrl();

            $curl = $this->curlFactory->create();
            $curl->setTimeout(15);
            $curl->get($baseUrl);

            if ($curl->getStatus() !== self::HTTP_OK) {
                $result = false;
            }
        }

        return $result;
    }
}
