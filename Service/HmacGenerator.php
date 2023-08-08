<?php
declare(strict_types=1);

namespace Rally\Checkout\Service;

use Rally\Checkout\Api\ConfigInterface;
use Rally\Checkout\Api\Service\HmacGeneratorInterface;

class HmacGenerator implements HmacGeneratorInterface
{
    public function __construct(
        private readonly ConfigInterface $rallyConfig
    ) {
    }

    /**
     * @inheritdoc
     */
    public function generateHmac(string $data): string
    {
        $secret = $this->rallyConfig->getApiKey();
        return base64_encode(hash_hmac('sha256', $data, $secret, true));
    }
}
