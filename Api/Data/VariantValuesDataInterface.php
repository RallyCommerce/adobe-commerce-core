<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface VariantValuesDataInterface
 * @api
 */
interface VariantValuesDataInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    public const KEY_VALUE_ID = 'product_option_value_id';

    /**
     * Get value ID
     *
     * @return string|null
     */
    public function getProductOptionValueId();

    /**
     * Set value ID
     *
     * @param string $valueId
     * @return $this
     */
    public function setProductOptionValueId($valueId);
}
