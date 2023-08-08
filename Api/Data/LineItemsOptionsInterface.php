<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface LineItemsOptionsInterface
 *
 * @api
 */
interface LineItemsOptionsInterface extends ExtensibleDataInterface
{
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_NAME = 'name';
    public const KEY_VALUE = 'value';
    public const KEY_POSITION = 'position';

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
     * Get option name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set option name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get option value
     *
     * @return string|null
     */
    public function getValue();

    /**
     * Set option value
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value);

    /**
     * Get option position
     *
     * @return int|null
     */
    public function getPosition();

    /**
     * Set option position
     *
     * @param int $position
     * @return $this
     */
    public function setPosition($position);
}
