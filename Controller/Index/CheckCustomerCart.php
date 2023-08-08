<?php

namespace Rally\Checkout\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CheckCustomerCart extends Action
{
    public function __construct(
        private readonly JsonFactory $resultJsonFactory,
        private readonly CheckoutSession $checkoutSession,
        protected Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Check customer cart status
     *
     * @return Json
     */
    public function execute(): Json
    {
        $result = true;
        try {
            $quote = $this->checkoutSession->getQuote();

            if ($quote && !$quote->getIsActive()) {
                $result = false;
            }
        } catch (NoSuchEntityException|LocalizedException $e) {
            $result = false;
        }

        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData(['status' => $result]);
    }
}
