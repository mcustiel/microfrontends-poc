<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Processors;

use Mcustiel\MicrofrontendsComposer\CacheManager;
use Mcustiel\MicrofrontendsComposer\ServiceExecutionData;
use Psr\Http\Message\ResponseInterface;

final class CacheControlResponseHeaderProcessor
{
    private CacheManager $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function process(ServiceExecutionData $proxyData): void
    {
        $response = $proxyData->getResponse();

        if ($response->hasHeader('Cache-Control')) {
            $value = $response->getHeader('Cache-Control');
            foreach ($value as $headerValue) {
                if (str_starts_with($headerValue, 's-maxage')) {
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
