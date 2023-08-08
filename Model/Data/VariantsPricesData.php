<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\VariantsPricesDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class VariantsPricesData extends AbstractExtensibleModel implements VariantsPricesDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCurrency()
    {
        return $this->getData(self::KEY_CURRENCY);
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrency($currency)
    {
        return $this->setData(self::KEY_CURRENCY, $currency);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice()
    {
        return $this->getData(self::KEY_PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice($price)
    {
        return $this->setData(self::KEY_PRICE, $price);
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscountedPrice()
    {
        return $this->getData(self::KEY_DISCOUNTED_PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountedPrice($discountedPrice)
    {
        return $this->setData(self::KEY_DISCOUNTED_PRICE, $discountedPrice);
    }
}
