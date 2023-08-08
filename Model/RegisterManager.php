<?php
declare(strict_types=1);

namespace Rally\Checkout\Model;

use Magento\Framework\App\Area;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Rally\Checkout\Api\Data\RegisterDataInterface;
use Rally\Checkout\Api\Data\RegisterDataInterfaceFactory;
use Magento\Theme\Block\Html\Header\Logo;
use Magento\Store\Model\App\Emulation;
use Rally\Checkout\Api\RegisterManagerInterface;
use Rally\Checkout\Api\Service\RequestValidatorInterface;

/**
 * Checkout RegisterManager model.
 * @api
 */
class RegisterManager implements RegisterManagerInterface
{
    public function __construct(
        public StoreManagerInterface $storeManager,
        public RegisterDataInterfaceFactory $registerDataFactory,
        public Logo $logo,
        public Emulation $appEmulation,
        public RequestValidatorInterface $requestValidator
    ) {
    }

    /**
     * @inheritdoc
     */
    public function get(string $orgId): RegisterDataInterface
    {
        $this->requestValidator->validate();
        $registerData = $this->registerDataFactory->create();

        $store = $this->storeManager->getStore();
        $storeId = $store->getId();

        $storeName = $store->getConfig('general/store_information/name');
        $registerData->setStoreName($storeName)
            ->setStoreUrl($store->getBaseUrl(UrlInterface::URL_TYPE_WEB));

        $this->appEmulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);
        $registerData->setStoreLogo($this->logo->getLogoSrc());
        $this->appEmulation->stopEnvironmentEmulation();

        return $registerData;
    }
}
