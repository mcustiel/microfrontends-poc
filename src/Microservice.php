<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer;

use Psr\Http\Message\UriInterface;

class Microservice
{
    private ServiceId $id;

    private UriInterface $baseUrl;

    private string $urlPrefix;

    public function __construct(ServiceId $id, UriInterface $baseUrl, string $urlPrefix = '')
    {
        $this->id = $id;
        $this->baseUrl = $baseUrl;
        $this->urlPrefix = $urlPrefix;
    }

    public function getId(): ServiceId
    {
        return $this->id;
    }

    public function getBaseUrl(): UriInterface
    {
        return $this->baseUrl;
    }

    public function getUrlPrefix(): string
    {
        return $this->urlPrefix;
    }
}
