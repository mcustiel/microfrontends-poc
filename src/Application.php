<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer;

use Mcustiel\MicrofrontendsComposer\Collections\ServiceDataCollection;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\DependencyInjection\Container;
use Zend\Diactoros\Uri;

class Application
{
    private Factory $factory;

    private Container $container;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
        $this->container = $this->factory->createContainer();
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
        $serverCollection = new ServiceDataCollection();
        $servers = $this->container->getParameter('servers');

        foreach ($servers as $id => $url) {
            $service = new Microservice(new ServiceId($id), new Uri($url));
            $serverCollection->add(
                new ServiceExecutionData($service, RequestModifier::getRequestForService($request, $service))
            );
        }

        return $serverCollection;
    }
}
