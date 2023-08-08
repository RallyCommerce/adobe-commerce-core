<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface for cart data
 *
 * @api
 */
interface CartDataInterface  extends ExtensibleDataInterface
{
    public const KEY_ORGANIZATION_ID = 'organization_id';
    public const KEY_EXTERNAL_ID = 'external_id';
    public const KEY_CUSTOMER = 'customer';
    public const KEY_DISCOUNT = 'discounts';
    public const KEY_LINE_ITEMS = 'line_items';
    public const KEY_SHIPPING_LINES = 'shipping_lines';
    public const KEY_SHIPPING_ID = 'shipping_id';
    public const KEY_NOTES = 'notes';
    public const KEY_SUBTOTAL = 'subtotal';
    public const KEY_TOTAL = 'total';
    public const KEY_SHIPPING_TOTAL = 'shipping_total';
    public const KEY_HANDLING_TOTAL = 'handling_total';
    public const KEY_CURRENCY = 'currency';
    public const KEY_TAXES = 'taxes';
    public const KEY_META = 'meta';

    /**
     * Get Organization ID
     *
     * @return string
     */
    public function getOrganizationId();

    /**
     * Set Organization ID
     *
     * @param string $organizationId
     * @return $this
     */
    public function setOrganizationId($organizationId);

    /**
     * Get External ID
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Set External ID
     *
     * @param string $externalId
     * @return $this
     */
    public function setExternalId($externalId);

    /**
     * Get Customer Data
     *
     * @return \Rally\Checkout\Api\Data\AddressInformationInterface
     */
    public function getCustomer();

    /**
     * Set Customer Data
     *
     * @param \Rally\Checkout\Api\Data\AddressInformationInterface $customer
     * @return $this
     */
    public function setCustomer($customer);

    /**
     * Get Discounts Data
     *
     * @return \Rally\Checkout\Api\Data\DiscountsInterface[]
     */
    public function getDiscounts();

    /**
     * Set Discounts Data
     *
     * @param array $discounts
     * @return $this
     */
    public function setDiscounts($discounts);

    /**
     * Get line items
     *
     * @return \Rally\Checkout\Api\Data\LineItemsInterface[]
     */
    public function getLineItems();

    /**
     * Set line items
     *
     * @param array $lineItems
     * @return $this
     */
    public function setLineItems($lineItems);

    /**
     * Get line items
     *
     * @return \Rally\Checkout\Api\Data\ShippingLinesDataInterface[]
     */
    public function getShippingLines();

    /**
     * Set line items
     *
     * @param array $shippingLines
     * @return $this
     */
    public function setShippingLines($shippingLines);

    /**
     * Get Selected Shipping Line External ID
     *
     * @return string
     */
    public function getSelectedShippingLineExternalId();

    /**
     * Set Selected Shipping Line External ID
     *
     * @param string $shippingExternalId
     * @return $this
     */
    public function setSelectedShippingLineExternalId($shippingExternalId);

    /**
     * Get Notes
     *
     * @return string
     */
    public function getNotes();

    /**
     * Set Notes
     *
     * @param string $notes
     * @return $this
     */
    public function setNotes($notes);

    /**
     * Get Subtotal
     *
     * @return float
     */
    public function getSubtotal();

    /**
     * Set Subtotal
     *
     * @param float $subtotal
     * @return $this
     */
    public function setSubtotal($subtotal);

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal();

    /**
     * Set total
     *
     * @param float $total
     * @return $this
     */
    public function setTotal($total);

    /**
     * Get Shipping total
     *
     * @return float
     */
    public function getShippingTotal();

    /**
     * Set Shipping total
     *
     * @param float $shippingTotal
     * @return $this
     */
    public function setShippingTotal($shippingTotal);

    /**
     * Get Handling total
     *
     * @return float
     */
    public function getHandlingTotal();

    /**
     * Set Handling total
     *
     * @param float $handlingTotal
     * @return $this
     */
    public function setHandlingTotal($handlingTotal);

    /**
     * Get Currency
     *
     * @return string
     */
    public function getCurrency();

    /**
     * Set Currency
     *
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency);

    /**
     * Get taxes
     *
     * @return \Rally\Checkout\Api\Data\TaxesDataInterface[]
     */
    public function getTaxes();

    /**
     * Set taxes
     *
     * @param array $taxes
     * @return $this
     */
    public function setTaxes($taxes);

    /**
     * Get Meta
     *
     * @return string
     */
    public function getMeta();

    /**
     * Set Meta
     *
     * @param string $meta
     * @return $this
     */
    public function setMeta($meta);
}
