<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Processors;

use Mcustiel\MicrofrontendsComposer\CacheManager;
use Mcustiel\MicrofrontendsComposer\ServiceExecutionData;
use Psr\Http\Message\ResponseInterface;

final class CacheControlResponseHeaderProcessor
{
    /** @var CacheManager */
    private $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function process(ServiceExecutionData $proxyData, ResponseInterface $response)
    {
        $serviceResponse = $proxyData->getResponse();

        if ($serviceResponse->hasHeader('Cache-Control')) {
            $value = $serviceResponse->getHeader('Cache-Control');
            foreach ($value as $headerValue) {
                if (strpos($headerValue, 's-maxage') === 0) {
                    $this->cachePage($headerValue, $proxyData);
                }
            }
        }
    }

    private function cachePage(string $headerValue, ServiceExecutionData $serviceExecutionData): void
    {
        $parts = explode('=', $headerValue);
        $cacheTtl = (int) $parts[1];
        if ($cacheTtl > 0) {
            $serviceId = $serviceExecutionData->getService()->getId()->asString();
            $urlPath = $serviceExecutionData->getRequest()->getUri()->getPath() . '?' . $serviceExecutionData->getRequest()->getUri()->getQuery();
            $html = $serviceExecutionData->getResponse()->getBody()->__toString();
            $serviceExecutionData->setResponse($serviceExecutionData->getResponse()->withAddedHeader('X-Cached-Microfrontend', $serviceId));
            $this->cacheManager->save($serviceId, $urlPath, $cacheTtl, $html);
        }
    }
}
