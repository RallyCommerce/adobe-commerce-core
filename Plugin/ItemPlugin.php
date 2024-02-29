<?php

declare(strict_types=1);

namespace Rally\Checkout\Plugin;

use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;

class ItemPlugin
{
    /**
     * Check product representation plugin
     *
     * @param Item $subject
     * @param bool $result
     * @param Product $product
     * @return bool
     */
    public function afterRepresentProduct(Item $subject, bool $result, Product $product): bool
    {
        $productOptions = $product->getCustomOptions();

        if ($result && isset($productOptions['info_buyRequest'])) {
            $option = $productOptions['info_buyRequest']->getValue();

            if (
                is_string($option) &&
                (strpos($option, 'is_ppo') !== false || strpos($option, 'is_order_bump') !== false)
            ) {
                $result = false;
            }
        }
        return $result;
    }
}
