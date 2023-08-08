<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface LineItemsInterface
 * @api
 */
interface LineItemsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_EXTERNAL_PRODUCT_ID = 'external_product_id';
    public const KEY_EXTERNAL_VARIANT_ID = 'external_variant_id';
    public const KEY_QUANTITY = 'quantity';
    public const KEY_TITLE = 'title';
    public const KEY_EXTERNAL_VENDOR = 'external_vendor';
    public const KEY_EXTERNAL_SKU = 'external_sku';
    public const KEY_IMAGE = 'image';
    public const KEY_INVENTORY_QUANTITY = 'inventory_quantity';
    public const KEY_REQUIRES_SHIPPING = 'requires_shipping';
    public const KEY_TAXABLE = 'taxable';
    public const KEY_POSITION = 'position';
    public const KEY_PRICE = 'price';
    public const KEY_DISCOUNT_PRICE = 'discounted_price';
    public const KEY_TAX_RATE = 'tax_rate';
    public const KEY_TAX_AMOUNT = 'tax_amount';
    public const KEY_SUBTOTAL = 'subtotal';
    public const KEY_TOTAL = 'total';
    public const KEY_DISCOUNT_AMOUNT = 'discount_amount';
    public const KEY_IS_REMOVABLE = 'is_removable';
    public const KEY_OPTIONS = 'options';
    public const KEY_CATEGORIES = 'categories';
    public const KEY_METAFIELDS = 'metafields';

    /**
     * Get External ID
     *
     * @return string|null
     */
    public function getExternalId();

    /**
     * Set External ID
     *
     * @param string $id
     * @return $this
     */
    public function setExternalId($id);

    /**
     * Get external product ID
     *
     * @return string|null
     */
    public function getExternalProductId();

    /**
     * Set external product ID
     *
     * @param string $productId
     * @return $this
     */
    public function setExternalProductId($productId);

    /**
     * Get external variant ID
     *
     * @return string|null
     */
    public function getExternalVariantId();

    /**
     * Set external variant ID
     *
     * @param string $variantId
     * @return $this
     */
    public function setExternalVariantId($variantId);

    /**
     * Get quantity
     *
     * @return int|null
     */
    public function getQuantity();

    /**
     * Set quantity
     *
     * @param int $qty
     * @return $this
     */
    public function setQuantity($qty);

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get external vendor
     *
     * @return string|null
     */
    public function getExternalVendor();

    /**
     * Set external vendor
     *
     * @param string $vendor
     * @return $this
     */
    public function setExternalVendor($vendor);

    /**
     * Get external SKU
     *
     * @return string|null
     */
    public function getExternalSku();

    /**
     * Set external SKU
     *
     * @param string $sku
     * @return $this
     */
    public function setExternalSku($sku);

    /**
     * Get image
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Set image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image);

    /**
     * Get inventory quantity
     *
     * @return int|null
     */
    public function getInventoryQuantity();

    /**
     * Set inventory quantity
     *
     * @param int $quantity
     * @return $this
     */
    public function setInventoryQuantity($quantity);

    /**
     * Get requires shipping
     *
     * @return bool
     */
    public function getRequiresShipping();

    /**
     * Set requires shipping
     *
     * @param bool $requireShipping
     * @return $this
     */
    public function setRequiresShipping($requireShipping);

    /**
     * Get taxable
     *
     * @return bool
     */
    public function getTaxable();

    /**
     * Set taxable
     *
     * @param bool $taxable
     * @return $this
     */
    public function setTaxable($taxable);

    /**
     * Get position
     *
     * @return int|null
     */
    public function getPosition();

    /**
     * Set position
     *
     * @param int $position
     * @return $this
     */
    public function setPosition($position);

    /**
     * Get price
     *
     * @return float|null
     */
    public function getPrice();

    /**
     * Set price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * Get discounted price
     *
     * @return float|null
     */
    public function getDiscountedPrice();

    /**
     * Set discounted price
     *
     * @param float $discountedPrice
     * @return $this
     */
    public function setDiscountedPrice($discountedPrice);

    /**
     * Get tax rate
     *
     * @return float|null
     */
    public function getTaxRate();

    /**
     * Set tax rate
     *
     * @param float $taxRate
     * @return $this
     */
    public function setTaxRate($taxRate);

    /**
     * Get tax amount
     *
     * @return float|null
     */
    public function getTaxAmount();

    /**
     * Set tax amount
     *
     * @param float $taxAmount
     * @return $this
     */
    public function setTaxAmount($taxAmount);

    /**
     * Get subtotal
     *
     * @return float|null
     */
    public function getSubtotal();

    /**
     * Set subtotal
     *
     * @param float $subtotal
     * @return $this
     */
    public function setSubtotal($subtotal);

    /**
     * Get total
     *
     * @return float|null
     */
    public function getTotal();

    /**
     * Set total
     *
     * @param float $total
     * @return $this
     */
    public function setTotal($total);

    /**
     * Get discount amount
     *
     * @return float|null
     */
    public function getDiscountAmount();

    /**
     * Set discount amount
     *
     * @param float $discountAmount
     * @return $this
     */
    public function setDiscountAmount($discountAmount);

    /**
     * Get is removable
     *
     * @return bool
     */
    public function getIsRemovable();

    /**
     * Set is removable
     *
     * @param bool $isRemovable
     * @return $this
     */
    public function setIsRemovable($isRemovable);

    /**
     * Get options
     *
     * @return \Rally\Checkout\Api\Data\LineItemsOptionsInterface[]
     */
    public function getOptions();

    /**
     * Set options
     *
     * @param array $options
     * @return $this
     */
    public function setOptions($options);

    /**
     * Get categories
     *
     * @return \Rally\Checkout\Api\Data\CategoriesDataInterface[]
     */
    public function getCategories();

    /**
     * Set categories
     *
     * @param array $categories
     * @return $this
     */
    public function setCategories($categories);

    /**
     * Get metafields
     *
     * @return array
     */
    public function getMetafields();

    /**
     * Set metafields
     *
     * @param array $metafields
     * @return $this
     */
    public function setMetafields($metafields);
}
