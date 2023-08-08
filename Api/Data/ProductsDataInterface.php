<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ProductsDataInterface
 *
 * @api
 */
interface ProductsDataInterface extends ExtensibleDataInterface
{
    public const KEY_ORGANIZATION_ID = 'organization_id';
    public const KEY_TITLE = 'title';
    public const KEY_BODY_HTML = 'body_html';
    public const KEY_VENDOR = 'vendor';
    public const KEY_PRODUCT_TYPE = 'product_type';
    public const KEY_INVENTORY_QUANTITY = 'inventory_quantity';
    public const KEY_INVENTORY_MANAGEMENT = 'inventory_management';
    public const KEY_IMG_SRC = 'img_src';
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_IS_SUBSCRIPTION_ONLY = 'is_subscription_only';
    public const KEY_OPTIONS = 'options';
    public const KEY_VARIANTS = 'variants';
    public const KEY_IMAGES = 'images';

    /**
     * Get Organization ID
     *
     * @return string|null
     */
    public function getOrganizationId();

    /**
     * Set Organization ID
     *
     * @param string $orgId
     * @return $this
     */
    public function setOrganizationId($orgId);

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get body HTML
     *
     * @return string|null
     */
    public function getBodyHtml();

    /**
     * Set body HTML
     *
     * @param string $html
     * @return $this
     */
    public function setBodyHtml($html);

    /**
     * Get vendor
     *
     * @return string|null
     */
    public function getVendor();

    /**
     * Set vendor
     *
     * @param string $vendor
     * @return $this
     */
    public function setVendor($vendor);

    /**
     * Get product type
     *
     * @return string|null
     */
    public function getProductType();

    /**
     * Set product type
     *
     * @param string $type
     * @return $this
     */
    public function setProductType($type);

    /**
     * Get qty
     *
     * @return int|null
     */
    public function getInventoryQuantity();

    /**
     * Set qty
     *
     * @param int $qty
     * @return $this
     */
    public function setInventoryQuantity($qty);

    /**
     * Get inventory management
     *
     * @return string|null
     */
    public function getInventoryManagement();

    /**
     * Set inventory management
     *
     * @param string $inventory
     * @return $this
     */
    public function setInventoryManagement($inventory);

    /**
     * Get img src
     *
     * @return string|null
     */
    public function getImgSrc();

    /**
     * Set img src
     *
     * @param string $imgSrc
     * @return $this
     */
    public function setImgSrc($imgSrc);

    /**
     * Get external ID
     *
     * @return string|null
     */
    public function getExternalId();

    /**
     * Set external ID
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId);

    /**
     * Get subscription
     *
     * @return bool|null
     */
    public function getIsSubscriptionOnly();

    /**
     * Set subscription
     *
     * @param bool $subscription
     * @return $this
     */
    public function setIsSubscriptionOnly($subscription);

    /**
     * Get options
     *
     * @return \Rally\Checkout\Api\Data\ProductOptionsDataInterface[]|null
     */
    public function getOptions();

    /**
     * Set options
     *
     * @param \Rally\Checkout\Api\Data\ProductOptionsDataInterface[] $options
     * @return $this
     */
    public function setOptions($options);

    /**
     * Get variants
     *
     * @return \Rally\Checkout\Api\Data\ProductVariantsDataInterface[]|null
     */
    public function getVariants();

    /**
     * Set variants
     *
     * @param \Rally\Checkout\Api\Data\ProductVariantsDataInterface[] $variants
     * @return $this
     */
    public function setVariants($variants);

    /**
     * Get images
     *
     * @return \Rally\Checkout\Api\Data\ProductImagesDataInterface[]
     */
    public function getImages();

    /**
     * Set images
     *
     * @param \Rally\Checkout\Api\Data\ProductImagesDataInterface[] $images
     * @return $this
     */
    public function setImages($images);
}
