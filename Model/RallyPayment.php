<?php

namespace Rally\Checkout\Model;

use Magento\Sales\Model\Order\Payment;
use Magento\Framework\Exception\LocalizedException;

/**
 * Rally Custom Payment Method Model
 */
class RallyPayment extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * Payment Method code
     *
     * @var string
     */
    protected $_code = 'rallypayment';

    /**
     * @var bool
     */
    protected $_isOffline = true;

    /**
     * @var string
     */
    protected $_infoBlockType = \Rally\Checkout\Block\Info::class;

    /**
     * @var bool
     */
    protected $_canUseCheckout = false;

    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param int|string|null|\Magento\Store\Model\Store $storeId
     *
     * @return mixed
     */
    public function getConfigData($field, $storeId = null)
    {
        if ($field == 'title') {
            try {
                $paymentInfo = $this->getInfoInstance();
            } catch (LocalizedException $e) {
                $paymentInfo = null;
            }

            if ($paymentInfo instanceof Payment) {
                $payment = $paymentInfo->getOrder()->getPayment();
                $paymentData = $payment->getAdditionalInformation("raw_details_info");

                if ($paymentData &&
                    isset($paymentData["payment_method_name"]) &&
                    $paymentData["payment_method_name"] != 'credit_card'
                ) {
                    return ucwords((string) $paymentData['payment_processor_name']);
                }
            }
        }

        return parent::getConfigData($field, $storeId);
    }
}
