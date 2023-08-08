<?php

namespace Rally\Checkout\ViewModel;

use Rally\Checkout\Api\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Checkout config view model.
 */
class CheckoutConfig implements ArgumentInterface
{
    public function __construct(
        private readonly ConfigInterface $rallyConfig,
        private readonly SerializerInterface $serializer,
        private readonly CheckoutSession $checkoutSession
    ) {
    }

    /**
     * Retrieve config data
     *
     * @return string
     */
    public function getConfigData(): string
    {
        $checkoutData["rallyConfig"] = [
            "enabled" => $this->rallyConfig->isEnabled(),
            "sandbox" => $this->rallyConfig->isSandboxMode(),
            "clientId" => $this->rallyConfig->getClientId()
        ];

        return $this->serializer->serialize($checkoutData);
    }

    /**
     * Retrieve load SDK config
     *
     * @return bool
     */
    public function isLoadSdk(): bool
    {
        return $this->rallyConfig->isLoadSdk();
    }

    /**
     * Retrieve Rally SDK URL
     *
     * @return string
     */
    public function getSdkUrl(): string
    {
        return $this->rallyConfig->getSdkUrl();
    }

    /**
     * Retrieve customer quote id from session
     *
     * @return string
     */
    public function getQuoteId(): string
    {
        $quoteId = "";

        try {
            $quote = $this->checkoutSession->getQuote();
        } catch (NoSuchEntityException|LocalizedException $e) {
            $quote = null;
        }

        if ($quote && $quote->getId()) {
            $quoteId = $this->rallyConfig->getFormattedId("quote", $quote->getId(), $quote->getCreatedAt());
        }
        return $quoteId;
    }
}
