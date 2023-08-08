<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ProductVariantsDataInterface
 *
 * @api
 */
interface ProductVariantsDataInterface extends ExtensibleDataInterface
{
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_QTY = 'inventory_quantity';
    public const KEY_POSITION = 'position';
    public const KEY_SKU = 'external_sku';
    public const KEY_SHIPPING = 'requires_shipping';
    public const KEY_TAXABLE = 'taxable';
    public const KEY_IMAGES = 'images';
    public const KEY_OPTION_VALUES = 'option_values';
    public const KEY_PRICES = 'prices';

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
     * Get inventory quantity
     *
     * @return int|null
     */
    public function getInventoryQuantity();

    /**
     * Set inventory quantity
     *
     * @param int $qty
     * @return $this
     */
    public function setInventoryQuantity($qty);

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
     * Get requires shipping
     *
     * @return bool|null
     */
    public function getRequiresShipping();

    /**
     * Set requires shipping
     *
     * @param bool $shipping
     * @return $this
     */
    public function setRequiresShipping($shipping);

    /**
     * Get taxable
     *
     * @return bool|null
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
     * Get images
     *
     * @return \Rally\Checkout\Api\Data\ProductImagesDataInterface[]|null
     */
    public function getImages();

    /**
     * Set images
     *
     * @param \Rally\Checkout\Api\Data\ProductImagesDataInterface[] $images
     * @return $this
     */
    public function setImages($images);

    /**
     * Get option values
     *
     * @return \Rally\Checkout\Api\Data\VariantValuesDataInterface[]|null
     */
    public function getOptionValues();

    /**
     * Set option values
     *
     * @param \Rally\Checkout\Api\Data\VariantValuesDataInterface[] $optionValues
     * @return $this
     */
    public function setOptionValues($optionValues);

    /**
     * Get prices
     *
     * @return \Rally\Checkout\Api\Data\VariantsPricesDataInterface[]|null
     */
    public function getPrices();

    /**
     * Set prices
     *
     * @param \Rally\Checkout\Api\Data\VariantsPricesDataInterface[] $prices
     * @return $this
     */
    public function setPrices($prices);
}
