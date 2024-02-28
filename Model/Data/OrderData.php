<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\OrderDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class OrderData extends AbstractExtensibleModel implements OrderDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getExternalId()
    {
        return $this->getData(self::KEY_EXTERNAL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::KEY_EXTERNAL_ID, $externalId);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalNumber()
    {
        return $this->getData(self::KEY_EXTERNAL_NUMBER);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalNumber($externalNumber)
    {
        return $this->setData(self::KEY_EXTERNAL_NUMBER, $externalNumber);
    }

    /**
     * {@inheritdoc}
     */
    public function getCartId()
    {
        return $this->getData(self::KEY_CART_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCartId($cartId)
    {
        return $this->setData(self::KEY_CART_ID, $cartId);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->getData(self::KEY_STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        return $this->setData(self::KEY_STATUS, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtotal()
    {
        return $this->getData(self::KEY_SUBTOTAL);
    }

    /**
     * {@inheritdoc}
     */
    public function setSubtotal($subtotal)
    {
        return $this->setData(self::KEY_SUBTOTAL, $subtotal);
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal()
    {
        return $this->getData(self::KEY_TOTAL);
    }

    /**
     * {@inheritdoc}
     */
    public function setTotal($total)
    {
        return $this->setData(self::KEY_TOTAL, $total);
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxAmount()
    {
        return $this->getData(self::KEY_TAX_AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxAmount($taxAmount)
    {
        return $this->setData(self::KEY_TAX_AMOUNT, $taxAmount);
    }

    /**
     * {@inheritdoc}
     */
    public function getDiscounts()
    {
        return $this->getData(self::KEY_DISCOUNTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscounts($discounts)
    {
        return $this->setData(self::KEY_DISCOUNTS, $discounts);
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxes()
    {
        return $this->getData(self::KEY_TAXES);
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxes($taxes)
    {
        return $this->setData(self::KEY_TAXES, $taxes);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingLines()
    {
        return $this->getData(self::KEY_SHIPPING_LINES);
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingLines($shippingLines)
    {
        return $this->setData(self::KEY_SHIPPING_LINES, $shippingLines);
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlingFee()
    {
        return $this->getData(self::KEY_HANDLING_FEE);
    }

    /**
     * {@inheritdoc}
     */
    public function setHandlingFee($handlingFee)
    {
        return $this->setData(self::KEY_HANDLING_FEE, $handlingFee);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingCost()
    {
        return $this->getData(self::KEY_SHIPPING_COST);
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingCost($shippingCost)
    {
        return $this->setData(self::KEY_SHIPPING_COST, $shippingCost);
    }

    /**
     * {@inheritdoc}
     */
    public function getLineItems()
    {
        return $this->getData(self::KEY_LINE_ITEMS);
    }

    /**
     * {@inheritdoc}
     */
    public function setLineItems($lineItems)
    {
        return $this->setData(self::KEY_LINE_ITEMS, $lineItems);
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta()
    {
        return $this->getData(self::KEY_META);
    }

    /**
     * {@inheritdoc}
     */
    public function setMeta($meta)
    {
        return $this->setData(self::KEY_META, $meta);
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentCreated()
    {
        return $this->getData(self::KEY_PAYMENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setPaymentCreated($payment)
    {
        return $this->setData(self::KEY_PAYMENT, $payment);
    }
}
