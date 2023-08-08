<?php

namespace Rally\Checkout\Service\Cart;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\SalesRule\Model\Rule;
use Magento\SalesRule\Model\Utility;
use Magento\Framework\Webapi\Exception;
use Magento\SalesRule\Model\RuleFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CouponManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Rally\Checkout\Api\Data\DiscountsInterfaceFactory;
use Rally\Checkout\Api\Service\RequestValidatorInterface;
use Magento\SalesRule\Model\Rule\Action\Discount\CalculatorFactory;

class DiscountManager
{
    private $quoteOldCoupon;

    public function __construct(
        protected Utility $utility,
        protected RuleFactory $ruleFactory,
        protected CalculatorFactory $calculatorFactory,
        protected CartRepositoryInterface $quoteRepository,
        protected DiscountsInterfaceFactory $discountFactory,
        protected CouponManagementInterface $couponManagement,
        protected RequestValidatorInterface $requestValidator
    ) {
    }

    /**
     * Get customer cart data
     *
     * @param CartInterface $quote
     * @return array
     */
    public function getCartData(CartInterface $quote): array
    {
        return $this->getDiscountData($quote, "hook");
    }

    /**
     * Update cart discount data
     *
     * @param CartInterface $quote
     * @param array $rallyCartData
     * @return void
     * @throws Exception
     */
    public function updateCartData(CartInterface $quote, array $rallyCartData): void
    {
        $hasCoupon = false;
        if (!empty($rallyCartData['discounts'])) {
            foreach ($rallyCartData['discounts'] as $discount) {
                if ($discount['type'] == 'coupon' && $discount['name'] != "") {
                    $hasCoupon = true;
                    $this->applyCoupon($discount['name'], $quote);
                }
            }
        }

        if (!$hasCoupon) {
            $quote->setCouponCode('')->collectTotals();
        }
    }

    /**
     * Get order data
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderData(OrderInterface $order): array
    {
        return $this->getDiscountData($order, "webhook");
    }

    /**
     * Get cart discount data
     *
     * @param CartInterface|OrderInterface $cart
     * @param string $type
     * @return array
     */
    private function getDiscountData(CartInterface|OrderInterface $cart, string $type): array
    {
        $discounts = [];
        $isFreeShippingApplied = $isShippingDiscountApplied = false;
        $appliedRules = $this->getAppliedRuleIds($cart);
        $discountAmount = $type == "hook"
            ? $this->getRuleDiscountData($cart, $appliedRules)
            : $cart->getDiscountAmount();
        $appliedRules = $this->getAppliedRuleIds($cart, $appliedRules);

        foreach ($appliedRules as $appliedRule) {
            try {
                $salesRule = $this->ruleFactory->create()->load($appliedRule);
            } catch (\Exception $e) {
                continue;
            }

            $discountType = $salesRule->getCouponType() == Rule::COUPON_TYPE_SPECIFIC ? "coupon" : "promotion";
            $discountSubType = $salesRule->getSimpleFreeShipping() ? "free_shipping" : "total_price";
            $discountName = $discountType == "coupon"
                ? $salesRule->getUseAutoGeneration() ? $cart->getCouponCode() : $salesRule->getCouponCode()
                : $salesRule->getName();

            if ($type == "hook") {
                if (!$isFreeShippingApplied && $discountSubType == "free_shipping") {
                    $isFreeShippingApplied = true;
                    $shippingAmount = $cart->getShippingAddress()->getShippingAmount();
                    $discountAmount[$salesRule->getRuleId()] += $cart->getRallyShippingAmount() - $shippingAmount;
                }
                $shippingDiscount = $cart->getShippingAddress()->getShippingDiscountAmount();
                if (!$isShippingDiscountApplied && $shippingDiscount && $salesRule->getApplyToShipping()) {
                    $isShippingDiscountApplied = true;
                    $discountData = $this->discountFactory->create();
                    $discountData->setExternalId($salesRule->getRuleId())
                        ->setType($discountType)
                        ->setSubtype("shipping")
                        ->setName($discountName)
                        ->setAmount($shippingDiscount);
                    $discounts[] = $discountData;
                }
                $discountData = $this->discountFactory->create();
                $discountData->setExternalId($salesRule->getRuleId())
                    ->setType($discountType)
                    ->setSubtype($discountSubType)
                    ->setName($discountName)
                    ->setAmount($discountAmount[$salesRule->getRuleId()]);
            } else {
                $discountData = [
                    "external_id" => $salesRule->getRuleId(),
                    "type" => $discountType,
                    "subtype" => $discountSubType,
                    "name" => $discountName,
                    "amount" => $discountSubType == "total_price" ? $discountAmount * -1 : 0
                ];
            }
            $discounts[] = $discountData;
        }
        return $discounts;
    }

    /**
     * Apply coupon
     *
     * @param string $couponCode
     * @param CartInterface $quote
     * @return void
     * @throws Exception
     */
    private function applyCoupon(string $couponCode, CartInterface $quote): void
    {
        if ($couponCode == $quote->getCouponCode()) {
            $this->quoteOldCoupon = $couponCode;
            return;
        }

        $error = false;
        try {
            $quote->setAppliedRuleIds('');
            $quote->getShippingAddress()->setAppliedRuleIds('');
            $quote->setCouponCode($couponCode);
            $this->quoteRepository->save($quote->collectTotals());
        } catch (\Exception|LocalizedException $e) {
            $error = true;
        }

        if ($error) {
            if ($this->quoteOldCoupon) {
                $this->applyCoupon($this->quoteOldCoupon, $quote);
            }
            $this->requestValidator->handleException('invalid_discount_code');
        } elseif ($quote->getCouponCode() != $couponCode) {
            $this->requestValidator->handleException('invalid_discount_code');
        }
    }

    /**
     * Get rule wise discount data
     *
     * @param CartInterface $quote
     * @param array $appliedRules
     * @return array
     */
    private function getRuleDiscountData(CartInterface $quote, array $appliedRules): array
    {
        $quote->getShippingAddress()->setAppliedRuleIds('');
        $quote->setAppliedRuleIds('');
        $quote->collectTotals();
        $cartAppliedRules = $this->getAppliedRuleIds($quote);
        $appliedRules = array_unique(array_merge($appliedRules, $cartAppliedRules));
        $items = $quote->getAllVisibleItems();
        $discounts = array_fill_keys($appliedRules, 0);

        foreach ($items as $item) {
            $attributes = $item->getExtensionAttributes();
            if ($attributes && $itemDiscounts = $attributes->getDiscounts()) {
                foreach ($itemDiscounts as $itemDiscount) {
                    if (isset($discounts[$itemDiscount->getRuleID()])) {
                        $discounts[$itemDiscount->getRuleID()] += $itemDiscount->getDiscountData()->getAmount();
                    } else {
                        $discounts[$itemDiscount->getRuleID()] = $itemDiscount->getDiscountData()->getAmount();
                    }
                }
            }
        }
        return $discounts;
    }

    /**
     * Get cart applied rule ids
     *
     * @param CartInterface|OrderInterface $quote
     * @param array $appliedRules
     * @return array
     */
    private function getAppliedRuleIds(CartInterface|OrderInterface $quote, array $appliedRules = []): array
    {
        return $quote->getAppliedRuleIds() ? explode(",", (string) $quote->getAppliedRuleIds()) : $appliedRules;
    }
}
