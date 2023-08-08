<?php

namespace Rally\Checkout\Plugin;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Quote\Model\ChangeQuoteControl;

class ChangeQuoteControlPlugin
{
    public function __construct(
        public Request $request
    ) {
    }

    /**
     * Allow to change the quote.
     *
     * @param ChangeQuoteControl $subject
     * @param bool $result
     * @param CartInterface $quote
     * @return bool
     */
    public function afterIsAllowed(ChangeQuoteControl $subject, bool $result, CartInterface $quote): bool
    {
        $requestHmac = $this->request->getHeader('X-HMAC-SHA256');

        if ($requestHmac) {
            $result = true;
        }

        return $result;
    }
}
