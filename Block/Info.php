<?php

namespace Rally\Checkout\Block;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * Base payment information block
 */
class Info extends \Magento\Payment\Block\Info
{
    /**
     * Add payment method information
     *
     * @param DataObject|array|null $transport
     * @return DataObject
     * @throws LocalizedException
     */
    protected function _prepareSpecificInformation($transport = null): DataObject
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $info = $this->getInfo();
        $paymentData = $info->getAdditionalInformation("raw_details_info");

        if ($paymentData &&
            isset($paymentData["card_brand"]) &&
            isset($paymentData["payment_method_name"]) &&
            $paymentData["payment_method_name"] == "credit_card"
        ) {
            $lastDigits = $paymentData["last4"] ?? "";
            $transport = new DataObject(
                [
                (string)__('Credit Card Type') => $paymentData["card_brand"],
                (string)__('Credit Card Number') => "xxxx-" . $lastDigits]
            );
        }
        return parent::_prepareSpecificInformation($transport);
    }
}
