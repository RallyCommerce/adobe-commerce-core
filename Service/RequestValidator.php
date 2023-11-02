<?php
declare(strict_types=1);

namespace Rally\Checkout\Service;

use Magento\Framework\Webapi\Rest\Request;
use Rally\Checkout\Api\ConfigInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Webapi\Exception as WebapiException;
use Rally\Checkout\Api\Service\RequestValidatorInterface;
use Rally\Checkout\Api\Service\HmacGeneratorInterface;

/**
 * Checkout RequestValidator Service
 * @api
 */
class RequestValidator implements RequestValidatorInterface
{
    public const HTTP_UNAVAILABLE = 502;
    public const HTTP_NOT_EXIST = 422;
    public const HTTP_PRECONDITION_FAILED = 412;
    public const HTTP_NOT_ACCEPTABLE = 406;

    public function __construct(
        private readonly Request $request,
        private readonly ConfigInterface $rallyConfig,
        private readonly StoreManagerInterface $storeManager,
        private readonly HmacGeneratorInterface $hmacGenerator
    ) {
    }

    /**
     * @inheritdoc
     */
    public function validate(): void
    {
        $requestHmac = $this->request->getHeader('X-HMAC-SHA256');
        $requestBody = $this->request->getContent();
        $magentoHmac = $this->hmacGenerator->generateHmac($requestBody);

        if (!$this->rallyConfig->isEnabled()) {
            throw new WebapiException(
                __('Service temporarily unavailable. Please refresh page or try again later.'),
                'platform_unavailable',
                self::HTTP_UNAVAILABLE
            );
        }

        if ($requestHmac != $magentoHmac) {
            throw new WebapiException(
                __('Invalid HMAC signature!'),
                'platform_unavailable',
                self::HTTP_NOT_ACCEPTABLE
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function handleException(string $code, array $details = []): void
    {
        switch ($code) {
            case 'cart_not_found':
                $message = "The requested cart doesn't exist.";
                break;
            case 'cart_contains_only_digital_items':
                $message = "The cart doesn't contain any physical items.";
                break;
            case 'invalid_discount_code':
                $message = "The coupon code you entered couldn't be applied to any items in your order.";
                break;
            case 'cart_has_been_updated':
                $message = "Your cart has been updated. Please review before proceeding.";
                break;
            case 'out_of_stock':
                $message = "There isn't enough stock.";
                break;
            case 'ppo_add_error':
                $message = "General error when adding a PPO";
                break;
            default:
                $message = "";
        }

        throw new WebapiException(
            __($message),
            $code,
            self::HTTP_NOT_EXIST,
            $details
        );
    }

    /**
     * @inheritdoc
     */
    public function handleOrderException(string $code, string $message): void
    {
        $httpCode = $code == 'cart_has_been_updated' ?
            self::HTTP_NOT_EXIST :
            self::HTTP_PRECONDITION_FAILED;
        throw new WebapiException(
            __($message),
            $code,
            $httpCode
        );
    }

    /**
     * @inheritdoc
     */
    public function handleMultiStoreCurrency(CartInterface $quote): void
    {
        $currentStore = $this->storeManager->getStore();

        if ($currentStore->getWebsiteId() != $quote->getStore()->getWebsiteId()) {
            $this->handleException('cart_not_found');
        } elseif ($currentStore->getId() != $quote->getStoreId()) {
            $this->storeManager->setCurrentStore($quote->getStore());
            $currentStore->setCurrentCurrencyCode($quote->getQuoteCurrencyCode());
        } else {
            $currentStore->setCurrentCurrencyCode($quote->getQuoteCurrencyCode());
        }
    }
}
