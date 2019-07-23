<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Processors;

use Mcustiel\MicrofrontendsComposer\ServiceExecutionData;
use Psr\Http\Message\ResponseInterface;

final class HeadersComposer
{
    /** @var SetCookieResponseHeaderProcessor */
    private $setCookieHeaderProcessor;
    /** @var CacheControlResponseHeaderProcessor */
    private $cacheControlHeaderProcessor;
    /** @var CopyResponseHeaderPostprocessor */
    private $copyResponseHeaderProcessor;

    public function __construct(
        SetCookieResponseHeaderProcessor $setCookieHeaderProcessor,
        CacheControlResponseHeaderProcessor $cacheControlHeaderProcessor,
        CopyResponseHeaderPostprocessor $copyResponseHeaderProcessor
    ) {
        $this->setCookieHeaderProcessor = $setCookieHeaderProcessor;
        $this->cacheControlHeaderProcessor = $cacheControlHeaderProcessor;
        $this->copyResponseHeaderProcessor = $copyResponseHeaderProcessor;
    }

    public function process(ServiceExecutionData $serviceExecutionData, ResponseInterface $response): ResponseInterface
    {
        $response = $this->setCookieHeaderProcessor->process($serviceExecutionData, $response);
        $this->cacheControlHeaderProcessor->process($serviceExecutionData, $response);
        $response = $this->copyResponseHeaderProcessor->process($serviceExecutionData, $response);

        return $response;
    }
}
