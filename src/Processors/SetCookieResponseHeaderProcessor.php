<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Processors;

use Mcustiel\MicrofrontendsComposer\ServiceExecutionData;
use Mcustiel\MicrofrontendsComposer\ServiceId;
use Psr\Http\Message\ResponseInterface;

final class SetCookieResponseHeaderProcessor
{
    public function process(ServiceExecutionData $serviceExecutionData, ResponseInterface $response): ResponseInterface
    {
        $serviceResponse = $serviceExecutionData->getResponse();

        if (!$serviceResponse->hasHeader('Set-Cookie')) {
            return $response;
        }
        $value = $this->replaceSessionCookiesForServer(
            $serviceResponse->getHeader('Set-Cookie'),
            $serviceExecutionData->getService()->getId()
        );

        return $response->withAddedHeader('Set-Cookie', $value);
    }

    private function replaceSessionCookiesForServer(array $cookieHeader, ServiceId $serverId): array
    {
        $setCookieHeader = [];
        foreach ($cookieHeader as $id => $cookie) {
            $setCookieHeader[$id] = str_replace(
                'PHPSESSID',
                'PHPSESSID-' . $serverId->asString(),
                $cookie
            );
        }

        return $setCookieHeader;
    }
}
