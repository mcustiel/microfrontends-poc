<?php

namespace Mcustiel\MicrofrontendsComposer;

use Psr\Cache\CacheItemPoolInterface;

class CacheManager
{
    /** @var CacheItemPoolInterface */
    private $cache;

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    public function isCached(string $serviceId, string $urlPath)
    {
        return $this->cache->hasItem($this->buildKey($serviceId, $urlPath));
    }

    public function get(string $serviceId, string $urlPath)
    {
        return $this->cache->getItem($this->buildKey($serviceId, $urlPath))->get();
    }

    public function save(string $serviceId, string $urlPath, int $ttl, string $html)
    {
        $item = $this->cache->getItem($this->buildKey($serviceId, $urlPath));
        $item->set($html);
        $item->expiresAfter($ttl);
        $this->cache->save($item);
    }

    private function buildKey(string $serviceId, string $urlPath): string
    {
        return md5($serviceId . ':' . $urlPath);
    }
}
