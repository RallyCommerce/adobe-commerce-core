<?php

namespace Rally\Checkout\Plugin;

use Magento\Tax\Model\Config;
use Magento\Store\Model\Store;
use Magento\Framework\Webapi\Rest\Request;

class TaxConfigPlugin
{
    public function __construct(
        private readonly Request $request
    ) {
    }

    /**
     * Check if necessary do product price conversion
     *
     * @param Config $subject
     * @param bool $result
     * @param null|int|string|Store $store
     * @return bool
     */
    public function afterNeedPriceConversion(Config $subject, bool $result, null|int|string|Store $store): bool
    {
        $requestHmac = $this->request->getHeader('X-HMAC-SHA256');

        if ($requestHmac && !$result) {
            $result = true;
        }

        return $result;
    }
}
