<?php

namespace Mcustiel\MicrofrontendsComposer;

use Mcustiel\MicrofrontendsComposer\Collections\ServiceDataCollection;
use Laminas\Diactoros\Response;

class RequestsPreprocessor
{
    /** @var CacheManager */
    private $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function getProxiesDataToExecute(ServiceDataCollection $servers): ServiceDataCollection
    {
        $serviceExecutionDataToRequest = new ServiceDataCollection();
        /** @var ServiceExecutionData $serviceExecutionData */
        foreach ($servers as $serviceExecutionData) {
            $request = $serviceExecutionData->getRequest()->getUri()->getPath() . '?' . $serviceExecutionData->getRequest()->getUri()->getQuery();
            if ($this->cacheManager->isCached($serviceExecutionData->getService()->getId()->asString(), $request)) {
                $this->setCachedResponseInServiceExecutionData($serviceExecutionData, $request);
            } else {
                $serviceExecutionDataToRequest->add($serviceExecutionData);
            }
        }

        return $serviceExecutionDataToRequest;
    }

    private function setCachedResponseInServiceExecutionData(ServiceExecutionData $serviceExecutionData, string $request): void
    {
        $serviceId = $serviceExecutionData->getService()->getId()->asString();
        $page = $this->cacheManager->get($serviceId, $request);
        $response = new Response(new StringStream($page));
        $response = $response->withAddedHeader('X-Cached-Microfrontend', $serviceId);
        $serviceExecutionData->setResponse($response);
    }
}
