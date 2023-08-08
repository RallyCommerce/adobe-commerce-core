<?php
declare(strict_types=1);

namespace Rally\Checkout\Model;

use Rally\Checkout\Service\CartMapper;
use Magento\Framework\Webapi\Rest\Request;
use Rally\Checkout\Api\CartManagerInterface;
use Rally\Checkout\Api\Data\CartDataInterface;
use Rally\Checkout\Api\Service\RequestValidatorInterface;

/**
 * Checkout CartManager model.
 * @api
 */
class CartManager implements CartManagerInterface
{
    public function __construct(
        public Request $request,
        public CartMapper $cartMapper,
        public RequestValidatorInterface $requestValidator
    ) {
    }

    /**
     * @inheritdoc
     */
    public function create(string $orgId): CartDataInterface
    {
        $this->requestValidator->validate();
        $rallyCartData = $this->request->getBodyParams();
        $externalId = $this->cartMapper->createCartWithItems($rallyCartData);
        return $this->cartMapper->mapCartsData($orgId, $externalId);
    }

    /**
     * @inheritdoc
     */
    public function get(string $orgId, string $externalId): CartDataInterface
    {
        $this->requestValidator->validate();
        return $this->cartMapper->mapCartsData($orgId, $externalId);
    }

    /**
     * @inheritdoc
     */
    public function save(string $orgId, string $externalId): CartDataInterface
    {
        $this->requestValidator->validate();
        $rallyCartData = $this->request->getBodyParams();
        return $this->cartMapper->updateCartsData($orgId, $externalId, $rallyCartData);
    }

    /**
     * @inheritdoc
     */
    public function addItems(string $orgId, string $externalId): CartDataInterface
    {
        $this->requestValidator->validate();
        $rallyCartData = $this->request->getBodyParams();
        $this->cartMapper->addItemsToCart($externalId, $rallyCartData['line_items']);
        return $this->cartMapper->mapCartsData($orgId, $externalId);
    }

    /**
     * @inheritdoc
     */
    public function removeItems(string $orgId, string $externalId): CartDataInterface
    {
        $this->requestValidator->validate();
        $rallyCartData = $this->request->getBodyParams();
        $this->cartMapper->removeItemsFromCart($externalId, $rallyCartData['line_items']);
        return $this->cartMapper->mapCartsData($orgId, $externalId);
    }
}
