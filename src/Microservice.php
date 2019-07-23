<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer;

use Psr\Http\Message\UriInterface;

class Microservice
{
    /** @var ServiceId */
    private $id;

    /** @var UriInterface */
    private $baseUrl;

    public function __construct(ServiceId $id, UriInterface $baseUrl)
    {
        $this->id = $id;
        $this->baseUrl = $baseUrl;
    }

    public function getId(): ServiceId
    {
        return $this->id;
    }

    public function getBaseUrl(): UriInterface
    {
        return $this->baseUrl;
    }
}
