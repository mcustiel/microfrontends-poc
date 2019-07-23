<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer;

use Psr\Http\Message\RequestInterface;

class RequestModifier
{
    const COOKIE_HEADER = 'Cookie';
    const HOST_HEADER = 'Host';
    const SESSION_COOKIE = 'PHPSESSID';
    const HEADER_SEPARATOR = ';';

    public static function getRequestForService(
        RequestInterface $request,
        Microservice $service
    ): RequestInterface {
        $request = self::getRequestWithCookiesForService($request, $service);
        $request = self::getRequestWithHostForService($request, $service);

        return self::getRequestWithUrlForService($request, $service);
    }

    public static function getRequestWithUrlForService(
        RequestInterface $request,
        Microservice $service
    ): RequestInterface {
        $uri = $service->getBaseUrl();

        return $request->withUri(
            $request->getUri()
                ->withScheme($uri->getScheme())
                ->withHost($uri->getHost())
        );
    }

    public static function getRequestWithHostForService(
        RequestInterface $request,
        Microservice $service
    ): RequestInterface {
        if ($request->hasHeader(self::HOST_HEADER)) {
            $request = $request->withHeader(self::HOST_HEADER, $service->getBaseUrl()->getHost());
        }

        return $request;
    }

    public static function getRequestWithCookiesForService(
        RequestInterface $request,
        Microservice $service
    ): RequestInterface {
        if ($request->hasHeader(self::COOKIE_HEADER)) {
            $newCookies = self::getCookiesForService(
                $request->getHeader(self::COOKIE_HEADER),
                $service->getId()->asString()
            );
            $request = $request->withHeader(
                self::COOKIE_HEADER,
                [implode(self::HEADER_SEPARATOR, $newCookies)]
            );
        }

        return $request;
    }

    private static function getCookiesForService(array $cookieHeader, string $serviceId): array
    {
        $cookies = explode(self::HEADER_SEPARATOR, $cookieHeader[0]);
        $newCookies = [];

        foreach ($cookies as $cookie) {
            $trimmedCookie = ltrim($cookie);
            if (strpos($trimmedCookie, self::SESSION_COOKIE) === 0) {
                if (strpos($trimmedCookie, sprintf('%s-%s=', self::SESSION_COOKIE, $serviceId)) === 0) {
                    $newCookies[] = str_replace("-{$serviceId}", '', $cookie);
                }
            } else {
                $newCookies[] = $cookie;
            }
        }

        return $newCookies;
    }
}
