<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface ProductImagesDataInterface
 * @api
 */
interface ProductImagesDataInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_POSITION = 'position';
    public const KEY_SRC = 'src';
    public const KEY_EXTERNAL_ID = 'external_id';

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
     * Get image src
     *
     * @return string|null
     */
    public function getSrc();

    /**
     * Set image src
     *
     * @param string $src
     * @return $this
     */
    public function setSrc($src);

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
}
