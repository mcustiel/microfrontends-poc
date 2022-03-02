<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class ServiceExecutionData
{
    private RequestInterface $request;

    private Microservice $service;

    private ResponseInterface $response;

    public function __construct(Microservice $service, RequestInterface $request)
    {
        $this->service = $service;
        $this->request = $request;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getService(): Microservice
    {
        return $this->service;
    }

    public function getResponse(): ResponseInterface
    {
        if ($this->response === null) {
            throw new RuntimeException('Trying to access a null value');
        }

        return $this->response;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
}
