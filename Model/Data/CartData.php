<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\CartDataInterface;

/**
 * Allows to get and set store config values
 */
class CartData extends AbstractExtensibleModel implements CartDataInterface
{
    /**
     * @inheritdoc
     */
    public function getOrganizationId()
    {
        return $this->getData(self::KEY_ORGANIZATION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrganizationId($organizationId)
    {
        return $this->setData(self::KEY_ORGANIZATION_ID, $organizationId);
    }

    /**
     * @inheritdoc
     */
    public function getExternalId()
    {
        return $this->getData(self::KEY_EXTERNAL_ID);
    }

    /**
     * @inheritdoc
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::KEY_EXTERNAL_ID, $externalId);
    }

    /**
     * @inheritdoc
     */
    public function getCustomer()
    {
        return $this->getData(self::KEY_CUSTOMER);
    }

    /**
     * @inheritdoc
     */
    public function setCustomer($customer)
    {
        return $this->setData(self::KEY_CUSTOMER, $customer);
    }

    /**
     * @inheritdoc
     */
    public function getDiscounts()
    {
        return $this->getData(self::KEY_DISCOUNT);
    }

    /**
     * @inheritdoc
     */
    public function setDiscounts($discounts)
    {
        return $this->setData(self::KEY_DISCOUNT, $discounts);
    }

    /**
     * @inheritdoc
     */
    public function getLineItems()
    {
        return $this->getData(self::KEY_LINE_ITEMS);
    }

    /**
     * @inheritdoc
     */
    public function setLineItems($lineItems)
    {
        return $this->setData(self::KEY_LINE_ITEMS, $lineItems);
    }

    /**
     * @inheritdoc
     */
    public function getShippingLines()
    {
        return $this->getData(self::KEY_SHIPPING_LINES);
    }

    /**
     * @inheritdoc
     */
    public function setShippingLines($shippingLines)
    {
        return $this->setData(self::KEY_SHIPPING_LINES, $shippingLines);
    }

    /**
     * @inheritdoc
     */
    public function getSelectedShippingLineExternalId()
    {
        return $this->getData(self::KEY_SHIPPING_ID);
    }

    /**
     * @inheritdoc
     */
    public function setSelectedShippingLineExternalId($shippingExternalId)
    {
        return $this->setData(self::KEY_SHIPPING_ID, $shippingExternalId);
    }

    /**
     * @inheritdoc
     */
    public function getNotes()
    {
        return $this->getData(self::KEY_NOTES);
    }

    /**
     * @inheritdoc
     */
    public function setNotes($notes)
    {
        return $this->setData(self::KEY_NOTES, $notes);
    }

    /**
     * @inheritdoc
     */
    public function getSubtotal()
    {
        return $this->getData(self::KEY_SUBTOTAL);
    }

    /**
     * @inheritdoc
     */
    public function setSubtotal($subtotal)
    {
        return $this->setData(self::KEY_SUBTOTAL, $subtotal);
    }

    /**
     * @inheritdoc
     */
    public function getTotal()
    {
        return $this->getData(self::KEY_TOTAL);
    }

    /**
     * @inheritdoc
     */
    public function setTotal($total)
    {
        return $this->setData(self::KEY_TOTAL, $total);
    }

    /**
     * @inheritdoc
     */
    public function getShippingTotal()
    {
        return $this->getData(self::KEY_SHIPPING_TOTAL);
    }

    /**
     * @inheritdoc
     */
    public function setShippingTotal($shippingTotal)
    {
        return $this->setData(self::KEY_SHIPPING_TOTAL, $shippingTotal);
    }

    /**
     * @inheritdoc
     */
    public function getHandlingTotal()
    {
        return $this->getData(self::KEY_HANDLING_TOTAL);
    }

    /**
     * @inheritdoc
     */
    public function setHandlingTotal($handlingTotal)
    {
        return $this->setData(self::KEY_HANDLING_TOTAL, $handlingTotal);
    }

    /**
     * @inheritdoc
     */
    public function getCurrency()
    {
        return $this->getData(self::KEY_CURRENCY);
    }

    /**
     * @inheritdoc
     */
    public function setCurrency($currency)
    {
        return $this->setData(self::KEY_CURRENCY, $currency);
    }

    /**
     * @inheritdoc
     */
    public function getTaxes()
    {
        return $this->getData(self::KEY_TAXES);
    }

    /**
     * @inheritdoc
     */
    public function setTaxes($taxes)
    {
        return $this->setData(self::KEY_TAXES, $taxes);
    }

    /**
     * @inheritdoc
     */
    public function getMeta()
    {
        return $this->getData(self::KEY_META);
    }

    /**
     * @inheritdoc
     */
    public function setMeta($meta)
    {
        return $this->setData(self::KEY_META, $meta);
    }
}
