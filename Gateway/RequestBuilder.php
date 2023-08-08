<?php

declare(strict_types=1);

namespace Rally\Checkout\Gateway;

use Rally\Checkout\Api\Gateway\RequestBuilderInterface;
use Rally\Checkout\Api\ConfigInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\RequestFactory;
use Rally\Checkout\Api\Service\HmacGeneratorInterface;

class RequestBuilder implements RequestBuilderInterface
{
    /**
     * @var string
     */
    private string $url;

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly ConfigInterface $config,
        private readonly HmacGeneratorInterface $hmacGenerator
    ) {
    }

    /**
     * @inheritdoc
     */
    public function build(string $type, string $body): Request
    {
        $hmacToken = $this->hmacGenerator->generateHmac($body);
        $requestData = [
            'method' => $type,
            'uri' => $this->url,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-HMAC-SHA256' => $hmacToken,
                'X-API-KEY' => $this->config->getApiKey()
            ],
            'body' => $body
        ];
        return $this->requestFactory->create($requestData);
    }

    /**
     * @inheritdoc
     */
    public function setUrl(string $requestUrl): RequestBuilderInterface
    {
        $this->url = $this->config->getRallyUrl() . $requestUrl;
        return $this;
    }
}
