<?php

namespace Mcustiel\MicrofrontendsComposer;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Mcustiel\MicrofrontendsComposer\Processors\BodyComposer;
use Mcustiel\MicrofrontendsComposer\Processors\CacheControlResponseHeaderProcessor;
use Mcustiel\MicrofrontendsComposer\Processors\CopyResponseHeaderPostprocessor;
use Mcustiel\MicrofrontendsComposer\Processors\HeadersComposer;
use Mcustiel\MicrofrontendsComposer\Processors\ResponsesComposer;
use Mcustiel\MicrofrontendsComposer\Processors\SetCookieResponseHeaderProcessor;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

class Factory
{
    public function createCacheManager(): CacheManager
    {
        return new CacheManager($this->createCacheAdapter());
    }

    public function createCacheAdapter(): CacheItemPoolInterface
    {
        return new ApcuAdapter();
    }

    public function createRequestsExecutor(): RequestsExecutor
    {
        return new RequestsExecutor(
            $this->createHttpClient(),
            $this->createRequestsPreprocessor(),
            $this->createResponsesComposer()
        );
    }

    public function createHttpClient(): ClientInterface
    {
        return new Client([
            'allow_redirects' => false,
        ]);
    }

    public function createRequestsPreprocessor(): RequestsPreprocessor
    {
        return new RequestsPreprocessor($this->createCacheManager());
    }

    public function createResponsesComposer(): ResponsesComposer
    {
        return new ResponsesComposer(
            $this->createBodyComposer(),
            $this->createHeadersComposer(),
            $this->createTemplateLoader()
        );
    }

    public function createBodyComposer(): BodyComposer
    {
        return new BodyComposer();
    }

    public function createTemplateloader(): HtmlTemplateLoader
    {
        return new HtmlTemplateLoader();
    }

    public function createHeadersComposer(): HeadersComposer
    {
        return new HeadersComposer(
            $this->createSetCookieResponseHeaderProcessor(),
            $this->createCacheControlResponseHeaderProcessor(),
            $this->createCopyResponseHeaderPostprocessor()
        );
    }

    public function createCopyResponseHeaderPostprocessor(): CopyResponseHeaderPostprocessor
    {
        return new CopyResponseHeaderPostprocessor();
    }

    public function createCacheControlResponseHeaderProcessor(): CacheControlResponseHeaderProcessor
    {
        return new CacheControlResponseHeaderProcessor($this->createCacheManager());
    }

    public function createSetCookieResponseHeaderProcessor(): SetCookieResponseHeaderProcessor
    {
        return new SetCookieResponseHeaderProcessor();
    }

    public function createResponseEmitter(): EmitterInterface
    {
        return new SapiEmitter();
    }

    public function createContainer(): Container
    {
        $containerBuilder = new ContainerBuilder();
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../config'));
        $loader->load('services.yml');
        $containerBuilder->compile();
        return $containerBuilder;
    }
}
