<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\ProductVariantsDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class ProductVariantsData extends AbstractExtensibleModel implements ProductVariantsDataInterface
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
    public function setExternalId($id)
    {
        return $this->setData(self::KEY_EXTERNAL_ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getInventoryQuantity()
    {
        return $this->getData(self::KEY_QTY);
    }

    /**
     * {@inheritdoc}
     */
    public function setInventoryQuantity($qty)
    {
        return $this->setData(self::KEY_QTY, $qty);
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->getData(self::KEY_POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        return $this->setData(self::KEY_POSITION, $position);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalSku()
    {
        return $this->getData(self::KEY_SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalSku($sku)
    {
        return $this->setData(self::KEY_SKU, $sku);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiresShipping()
    {
        return $this->getData(self::KEY_SHIPPING);
    }

    /**
     * {@inheritdoc}
     */
    public function setRequiresShipping($shipping)
    {
        return $this->setData(self::KEY_SHIPPING, $shipping);
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxable()
    {
        return $this->getData(self::KEY_TAXABLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxable($taxable)
    {
        return $this->setData(self::KEY_TAXABLE, $taxable);
    }

    /**
     * {@inheritdoc}
     */
    public function getImages()
    {
        return $this->getData(self::KEY_IMAGES);
    }

    /**
     * {@inheritdoc}
     */
    public function setImages($images)
    {
        return $this->setData(self::KEY_IMAGES, $images);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionValues()
    {
        return $this->getData(self::KEY_OPTION_VALUES);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptionValues($optionValues)
    {
        return $this->setData(self::KEY_OPTION_VALUES, $optionValues);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrices()
    {
        return $this->getData(self::KEY_PRICES);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrices($prices)
    {
        return $this->setData(self::KEY_PRICES, $prices);
    }
}
