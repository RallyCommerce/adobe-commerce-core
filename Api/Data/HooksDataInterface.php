<?php

namespace Rally\Checkout\Api\Data;

/**
 * Interface HooksDataInterface
 * @api
 */
interface HooksDataInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const KEY_APP_ID = 'app_id';
    const KEY_TYPE = 'type';
    const KEY_URL = 'url';

    /**
     * Get app ID
     *
     * @return string|null
     */
    public function getAppId(): ?string;

    /**
     * Set app ID
     *
     * @param string $appId
     * @return $this
     */
    public function setAppId(string $appId): static;

    /**
     * Get type
     *
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): static;

    /**
     * Get URL
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Set URL
     *
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): static;
}
