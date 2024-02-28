<?php

namespace Rally\Checkout\Service\Cart;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Rally\Checkout\Api\Data\TaxesDataInterfaceFactory;

class TaxManager
{
    public function __construct(
        protected TaxesDataInterfaceFactory $taxesDataFactory
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
        $appliedTaxes = $quote->getShippingAddress()->getAppliedTaxes() ?? [];
        $cartTaxesData = [];

        foreach ($appliedTaxes as $appliedTax) {
            $taxesData = $this->taxesDataFactory->create();

            $taxesData->setTitle($appliedTax['id'])
                ->setTaxRate((float) ($appliedTax['percent'] / 100))
                ->setTaxAmount($appliedTax['amount']);

            $cartTaxesData[] = $taxesData;
        }
        return $cartTaxesData;
    }

    /**
     * Get order data
     *
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderData(OrderInterface $order): array
    {
        $orderTaxesData = [];
        $appliedTaxes = $order->getExtensionAttributes()->getAppliedTaxes();

        if ($appliedTaxes) {
            foreach ($appliedTaxes as $appliedTax) {
                $taxesData = [
                    "title" => $appliedTax['title'] ?? '',
                    "tax_rate" => (float) ($appliedTax['percent'] / 100),
                    "tax_amount" => (float) $appliedTax['amount'],
                    "meta" => []
                ];
                $orderTaxesData[] = $taxesData;
            }
        }
        return $orderTaxesData;
    }
}
