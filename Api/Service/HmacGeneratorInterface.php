<?php

namespace Rally\Checkout\Api\Service;

/**
 * Interface HmacGeneratorInterface
 */
interface HmacGeneratorInterface
{
    /**
     * Generate HMAC for request
     *
     * @param string $data
     * @return string
     */
    public function generateHmac(string $data): string;
}
