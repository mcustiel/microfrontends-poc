<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer;

use Mcustiel\MicrofrontendsComposer\Collections\ServiceDataCollection;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Uri;

class Application
{
    /** @var Factory */
    private $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function main(): void
    {
        $request = ServerRequestFactory::createToForward();

        $servers = $this->getProxiesData($request);
        $response = $this->factory->createRequestsExecutor()->execute($servers);
        $this->factory->createResponseEmitter()->emit($response);
    }

    private function getProxiesData(ServerRequestInterface $request): ServiceDataCollection
    {
        $servers = new ServiceDataCollection();

        $service = new Microservice(new ServiceId('login'), new Uri('http://login-web-server'));
        $request = RequestModifier::getRequestForService($request, $service);
        $servers->add(new ServiceExecutionData($service, $request));

        $service = new Microservice(new ServiceId('catalog'), new Uri('http://catalog-web-server'));
        $request = RequestModifier::getRequestForService($request, $service);
        $servers->add(new ServiceExecutionData($service, $request));

        return $servers;
    }
}
