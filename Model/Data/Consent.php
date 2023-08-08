<?php

namespace Rally\Checkout\Model\Data;

use Magento\Framework\Model\AbstractExtensibleModel;
use Rally\Checkout\Api\Data\ConsentInterface;

/**
 * Class Address Data Model implementing the Consent interface
 *
 * @api
 */
class Consent extends AbstractExtensibleModel implements ConsentInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($consent)
    {
        return $this->setData(self::EMAIL, $consent);
    }
}
