<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\ProductImagesDataInterface;

/**
 * @codeCoverageIgnoreStart
 */
class ProductImagesData extends AbstractExtensibleModel implements ProductImagesDataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->getData(self::KEY_POSITION);
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        return $this->setData(self::KEY_POSITION, $position);
    }

    /**
     * {@inheritdoc}
     */
    public function getSrc()
    {
        return $this->getData(self::KEY_SRC);
    }

    /**
     * {@inheritdoc}
     */
    public function setSrc($src)
    {
        return $this->setData(self::KEY_SRC, $src);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalId()
    {
        return $this->getData(self::KEY_EXTERNAL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalId($externalId)
    {
        return $this->setData(self::KEY_EXTERNAL_ID, $externalId);
    }
}
