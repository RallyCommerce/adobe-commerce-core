<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface CategoriesDataInterface
 *
 * @api
 */
interface CategoriesDataInterface extends ExtensibleDataInterface
{
    public const KEY_ORGANIZATION_ID = 'organization_id';
    public const KEY_TITLE = 'title';
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_IMAGE_URL = 'image_url';
    public const KEY_META = 'meta';

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
     * Get Image URL
     *
     * @return string|null
     */
    public function getImageUrl();

    /**
     * Set Image URL
     *
     * @param string $imgUrl
     * @return $this
     */
    public function setImageUrl($imgUrl);

    /**
     * Get meta
     *
     * @return string|null
     */
    public function getMeta();

    /**
     * Set meta
     *
     * @param string $meta
     * @return $this
     */
    public function setMeta($meta);
}
