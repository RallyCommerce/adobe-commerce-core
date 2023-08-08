<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\CategoriesDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class CategoriesData extends AbstractExtensibleModel implements CategoriesDataInterface
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
    public function getImageUrl()
    {
        return $this->getData(self::KEY_IMAGE_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setImageUrl($imgUrl)
    {
        return $this->setData(self::KEY_IMAGE_URL, $imgUrl);
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
}
