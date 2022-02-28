<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Processors;

use Mcustiel\MicrofrontendsComposer\ServiceExecutionData;
use Psr\Http\Message\ResponseInterface;

use function in_array;

final class CopyResponseHeaderPostprocessor
{
    const IGNORED_HEADERS = [
        'Server', 'Date', 'Content-Type', 'Transfer-Encoding',
        'Connection', 'X-Powered-By', 'Expires', 'Pragma',
        'Cache-Control', 'Set-Cookie', 'Content-Length'
    ];

    public function process(ServiceExecutionData $serviceExecutionData, ResponseInterface $response): ResponseInterface
    {
        foreach ($serviceExecutionData->getResponse()->getHeaders() as $name => $value) {
            if (in_array($name, self::IGNORED_HEADERS, true)) {
                continue;
            }
            $response = $response->withHeader($name, $value);
        }

        return $response;
    }
}
