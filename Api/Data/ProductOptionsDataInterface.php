<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface ProductOptionsDataInterface
 * @api
 */
interface ProductOptionsDataInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_NAME = 'name';
    public const KEY_POSITION = 'position';
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_OPTION_VALUE = 'option_value';

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
     * Get option value
     *
     * @return \Rally\Checkout\Api\Data\ProductOptionValuesDataInterface[]|null
     */
    public function getOptionValue();

    /**
     * Set option value
     *
     * @param \Rally\Checkout\Api\Data\ProductOptionValuesDataInterface[] $optionValue
     * @return $this
     */
    public function setOptionValue($optionValue);
}
