<?php

namespace Rally\Checkout\Api\Gateway;

use GuzzleHttp\Psr7\Request;

interface RequestBuilderInterface
{
    /**
     * Build GET request,
     * set authorization headers.
     *
     * @param string $type
     * @param string $body
     * @return Request
     */
    public function build(string $type, string $body): Request;

    /**
     * Set Rally hooks url
     *
     * @param string $requestUrl
     * @return $this
     */
    public function setUrl(string $requestUrl): self;
}
