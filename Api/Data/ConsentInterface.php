<?php

namespace Rally\Checkout\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Customer consent interface.
 *
 * @api
 */
interface ConsentInterface extends ExtensibleDataInterface
{
    public const EMAIL = 'email';

    /**
     * Get email consent
     *
     * @return bool
     */
    public function getEmail();

    /**
     * Set email consent
     *
     * @param bool $consent
     * @return $this
     */
    public function setEmail($consent);
}
