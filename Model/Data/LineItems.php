<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\LineItemsInterface;

/**
 * @codeCoverageIgnoreStart
 */
class LineItems extends AbstractExtensibleModel implements LineItemsInterface
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
    public function getExternalProductId()
    {
        return $this->getData(self::KEY_EXTERNAL_PRODUCT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalProductId($productId)
    {
        return $this->setData(self::KEY_EXTERNAL_PRODUCT_ID, $productId);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalVariantId()
    {
        return $this->getData(self::KEY_EXTERNAL_VARIANT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalVariantId($variantId)
    {
        return $this->setData(self::KEY_EXTERNAL_VARIANT_ID, $variantId);
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity()
    {
        return $this->getData(self::KEY_QUANTITY);
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity($qty)
    {
        return $this->setData(self::KEY_QUANTITY, $qty);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->getData(self::KEY_TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        return $this->setData(self::KEY_TITLE, $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalVendor()
    {
        return $this->getData(self::KEY_EXTERNAL_VENDOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalVendor($vendor)
    {
        return $this->setData(self::KEY_EXTERNAL_VENDOR, $vendor);
    }

    /**
     * {@inheritdoc}
     */
    public function getSku()
    {
        return $this->getData(self::KEY_SKU);
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        return $this->setData(self::KEY_SKU, $sku);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage()
    {
        return $this->getData(self::KEY_IMAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setImage($image)
    {
        return $this->setData(self::KEY_IMAGE, $image);
    }

    /**
     * {@inheritdoc}
     */
    public function getInventoryQuantity()
    {
        return $this->getData(self::KEY_INVENTORY_QUANTITY);
    }

    /**
     * {@inheritdoc}
     */
    public function setInventoryQuantity($quantity)
    {
        return $this->setData(self::KEY_INVENTORY_QUANTITY, $quantity);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiresShipping()
    {
        return $this->getData(self::KEY_REQUIRES_SHIPPING);
    }

    /**
     * {@inheritdoc}
     */
    public function setRequiresShipping($requireShipping)
    {
        return $this->setData(self::KEY_REQUIRES_SHIPPING, $requireShipping);
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
        return $this->getData(self::KEY_DISCOUNT_PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountedPrice($discountedPrice)
    {
        return $this->setData(self::KEY_DISCOUNT_PRICE, $discountedPrice);
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxRate()
    {
        return $this->getData(self::KEY_TAX_RATE);
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxRate($taxRate)
    {
        return $this->setData(self::KEY_TAX_RATE, $taxRate);
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
    public function getDiscountAmount()
    {
        return $this->getData(self::KEY_DISCOUNT_AMOUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscountAmount($discountAmount)
    {
        return $this->setData(self::KEY_DISCOUNT_AMOUNT, $discountAmount);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsRemovable()
    {
        return $this->getData(self::KEY_IS_REMOVABLE);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsRemovable($isRemovable)
    {
        return $this->setData(self::KEY_IS_REMOVABLE, $isRemovable);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->getData(self::KEY_OPTIONS);
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        return $this->setData(self::KEY_OPTIONS, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategories()
    {
        return $this->getData(self::KEY_CATEGORIES);
    }

    /**
     * {@inheritdoc}
     */
    public function setCategories($categories)
    {
        return $this->setData(self::KEY_CATEGORIES, $categories);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetafields()
    {
        return $this->getData(self::KEY_METAFIELDS);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetafields($metafields)
    {
        return $this->setData(self::KEY_METAFIELDS, $metafields);
    }
}
