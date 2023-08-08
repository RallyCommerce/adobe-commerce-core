<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface DiscountsInterface
 * @api
 */
interface DiscountsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_TYPE = 'type';
    public const KEY_SUBTYPE = 'subtype';
    public const KEY_NAME = 'name';
    public const KEY_AMOUNT = 'amount';

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
     * Get type
     *
     * @return string|null
     */
    public function getType();

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * Get subtype
     *
     * @return string|null
     */
    public function getSubtype();

    /**
     * Set subtype
     *
     * @param string $subtype
     * @return $this
     */
    public function setSubtype($subtype);

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
     * Get amount
     *
     * @return float|null
     */
    public function getAmount();

    /**
     * Set amount
     *
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount);
}
