<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\ProductsDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class ProductsData extends AbstractExtensibleModel implements ProductsDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrganizationId()
    {
        return $this->getData(self::KEY_ORGANIZATION_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setOrganizationId($orgId)
    {
        return $this->setData(self::KEY_ORGANIZATION_ID, $orgId);
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
    public function getBodyHtml()
    {
        return $this->getData(self::KEY_BODY_HTML);
    }

    /**
     * {@inheritdoc}
     */
    public function setBodyHtml($html)
    {
        return $this->setData(self::KEY_BODY_HTML, $html);
    }

    /**
     * {@inheritdoc}
     */
    public function getVendor()
    {
        return $this->getData(self::KEY_VENDOR);
    }

    /**
     * {@inheritdoc}
     */
    public function setVendor($vendor)
    {
        return $this->setData(self::KEY_VENDOR, $vendor);
    }

    /**
     * {@inheritdoc}
     */
    public function getProductType()
    {
        return $this->getData(self::KEY_PRODUCT_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setProductType($type)
    {
        return $this->setData(self::KEY_PRODUCT_TYPE, $type);
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
    public function setInventoryQuantity($qty)
    {
        return $this->setData(self::KEY_INVENTORY_QUANTITY, $qty);
    }

    /**
     * {@inheritdoc}
     */
    public function getInventoryManagement()
    {
        return $this->getData(self::KEY_INVENTORY_MANAGEMENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setInventoryManagement($inventory)
    {
        return $this->setData(self::KEY_INVENTORY_MANAGEMENT, $inventory);
    }

    /**
     * {@inheritdoc}
     */
    public function getImgSrc()
    {
        return $this->getData(self::KEY_IMG_SRC);
    }

    /**
     * {@inheritdoc}
     */
    public function setImgSrc($imgSrc)
    {
        return $this->setData(self::KEY_IMG_SRC, $imgSrc);
    }

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
    public function getIsSubscriptionOnly()
    {
        return $this->getData(self::KEY_IS_SUBSCRIPTION_ONLY);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsSubscriptionOnly($subscription)
    {
        return $this->setData(self::KEY_IS_SUBSCRIPTION_ONLY, $subscription);
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
    public function getVariants()
    {
        return $this->getData(self::KEY_VARIANTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setVariants($variants)
    {
        return $this->setData(self::KEY_VARIANTS, $variants);
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
}
