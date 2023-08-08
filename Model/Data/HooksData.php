<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\HooksDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class HooksData extends AbstractExtensibleModel implements HooksDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAppId(): ?string
    {
        return $this->getData(self::KEY_APP_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setAppId(string $appId): static
    {
        return $this->setData(self::KEY_APP_ID, $appId);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): ?string
    {
        return $this->getData(self::KEY_TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type): static
    {
        return $this->setData(self::KEY_TYPE, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(): ?string
    {
        return $this->getData(self::KEY_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl($url): static
    {
        return $this->setData(self::KEY_URL, $url);
    }
}
