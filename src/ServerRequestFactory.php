<?php

namespace Mcustiel\MicrofrontendsComposer;

use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\ServerRequestFactory as ZendRequestFactory;

class ServerRequestFactory extends ZendRequestFactory
{
    public static function createToForward(): ServerRequestInterface
    {
        $request = parent::fromGlobals();
        $serverParams = $request->getServerParams();
        $request = $request->withHeader(
            'Forwarded',
            [
                'by:' . $serverParams['SERVER_ADDR']
                . ';for:' . $serverParams['REMOTE_ADDR']
                . ';Host: ' . $serverParams['HTTP_HOST'],
            ]
        );

        return $request;
    }
}
