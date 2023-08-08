<?php

namespace Rally\Checkout\Plugin;

use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\Webapi\Rest\Response;
use Magento\Framework\Serialize\Serializer\Json;
use Rally\Checkout\Api\Service\HmacGeneratorInterface;

class ResponsePlugin
{
    public function __construct(
        private readonly Json $json,
        private readonly Request $request,
        private readonly HmacGeneratorInterface $hmacGenerator
    ) {
    }

    /**
     * @param Response $subject
     * @param string $value
     * @return array
     */
    public function beforeSetBody(Response $subject, string $value): array
    {
        $requestHmac = $this->request->getHeader('X-HMAC-SHA256');

        if ($requestHmac) {
            $value = $this->mapErrorFormat($value);
            $magentoHmac = $this->hmacGenerator->generateHmac($value);
            $subject->setHeader('X-HMAC-SHA256', $magentoHmac, true);
        }
        return [$value];
    }

    /**
     * @param string $value
     * @return bool|string
     */
    private function mapErrorFormat(string $value): bool|string
    {
        if (str_contains($value, 'message') && str_contains($value, 'code')) {
            $response = $this->json->unserialize($value);

            $response["error"] = $response['message'] ?? null;
            unset($response['message']);

            $response["error_code"] = $response["code"] ?? null;
            unset($response['code']);

            if (isset($response["parameters"])) {
                $response["meta"] = $response["parameters"];
                unset($response['parameters']);
            }
            $value = $this->json->serialize($response);
        }
        return $value;
    }
}
