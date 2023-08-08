<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface ProductOptionValuesDataInterface
 * @api
 */
interface ProductOptionValuesDataInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_NAME = 'name';
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_POSITION = 'position';

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

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
}
