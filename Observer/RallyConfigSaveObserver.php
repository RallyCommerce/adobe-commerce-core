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

/**
 * Rally config save observer
 */
class RallyConfigSaveObserver implements ObserverInterface
{
    public function __construct(
        private readonly ConfigInterface $rallyConfig,
        private readonly HookManagerInterface $hookManager,
        private readonly MessageManagerInterface $messageManager
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
}
