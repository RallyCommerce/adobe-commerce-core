<?php
declare(strict_types=1);

namespace Rally\Checkout\Model;

use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Rally\Checkout\Api\ShippingZonesManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Rally\Checkout\Api\Service\RequestValidatorInterface;

/**
 * Checkout ShippingZonesManager model.
 * @api
 */
class ShippingZonesManager implements ShippingZonesManagerInterface
{
    public function __construct(
        public StoreManagerInterface $storeManager,
        public ScopeConfigInterface $scopeConfig,
        public RequestValidatorInterface $requestValidator
    ) {
    }

    /**
     * @inheritdoc
     */
    public function get(string $orgId)
    {
        $this->requestValidator->validate();
        $store = $this->storeManager->getStore();

        $countries = $this->scopeConfig->getValue(
            'general/country/allow',
            ScopeInterface::SCOPE_STORES,
            $store->getId()
        );

        $data = [];
        foreach (explode(',', $countries) as $country) {
            $data[$country] = ["states" => ["*"]];
        }

        $data['REST_OF_WORLD'] = false;
        return $data;
    }
}
