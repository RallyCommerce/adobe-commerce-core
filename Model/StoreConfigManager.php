<?php
declare(strict_types=1);

namespace Rally\Checkout\Model;

use Magento\Framework\App\Area;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Rally\Checkout\Api\Data\StoreConfigInterface;
use Rally\Checkout\Api\Data\StoreConfigInterfaceFactory;
use Magento\Theme\Block\Html\Header\Logo;
use Magento\Store\Model\App\Emulation;
use Magento\Directory\Model\RegionFactory;
use Rally\Checkout\Api\Service\RequestValidatorInterface;
use Rally\Checkout\Api\StoreConfigManagerInterface;

/**
 * Checkout StoreConfigManager model.
 * @api
 */
class StoreConfigManager implements StoreConfigManagerInterface
{
    protected array $configPaths = [
        'setStoreName' => 'general/store_information/name',
        'setStorePhone' => 'general/store_information/phone',
        'setStoreLocale' => 'general/locale/code',
        'setTimezone' => 'general/locale/timezone'
    ];

    protected array $addressConfigPaths = [
        'street_line1',
        'street_line2',
        'city',
        'region_id',
        'postcode'
    ];

    public function __construct(
        public StoreManagerInterface $storeManager,
        public StoreConfigInterfaceFactory $storeConfigFactory,
        public Logo $logo,
        public Emulation $appEmulation,
        public RegionFactory $regionFactory,
        public RequestValidatorInterface $requestValidator
    ) {
    }

    /**
     * @inheritdoc
     */
    public function get(string $orgId): StoreConfigInterface
    {
        $this->requestValidator->validate();
        $storeConfig = $this->storeConfigFactory->create();

        $store = $this->storeManager->getStore();
        $storeId = $store->getId();
        $country = $store->getConfig('general/store_information/country_id') ??
            $store->getConfig('general/country/default');

        $storeConfig->setOrganizationId($orgId)
            ->setExternalId($storeId)
            ->setCountryCode($country)
            ->setCurrency($store->getCurrentCurrencyCode())
            ->setStoreUrl($store->getBaseUrl(UrlInterface::URL_TYPE_WEB))
            ->setNativeCheckoutUrl($store->getUrl('checkout', ['_secure' => true]))
            ->setCartUrl($store->getUrl('checkout/cart', ['_secure' => true]));

        $this->appEmulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);
        $storeConfig->setLogoUrl($this->logo->getLogoSrc());
        $this->appEmulation->stopEnvironmentEmulation();

        foreach ($this->configPaths as $methodName => $configPath) {
            $configValue = $store->getConfig($configPath);
            $storeConfig->$methodName($configValue);
        }

        $address = '';
        foreach ($this->addressConfigPaths as $addressConfigPath) {
            $value = $store->getConfig('general/store_information/' . $addressConfigPath);

            if ($addressConfigPath == 'region_id' && $value) {
                $value = $this->regionFactory->create()->load($value)->getName() . ',';
            }

            $address = $value ? $address . " " . $value : $address;
        }
        $storeConfig->setAddress(trim((string) $address ? $address : $country));
        return $storeConfig;
    }
}
