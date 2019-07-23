<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Pool;
use Mcustiel\MicrofrontendsComposer\Collections\ServiceDataCollection;
use Mcustiel\MicrofrontendsComposer\Processors\ResponsesComposer;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class RequestsExecutor
{
    const CONCURRENT_REQUESTS_AMOUNT = 3;

    /** @var ClientInterface */
    private $client;

    /** @var RequestsPreprocessor */
    private $requestsPreprocessor;

    /** @var ResponsesComposer */
    private $responsesComposer;

    public function __construct(
        ClientInterface $httpClient,
        RequestsPreprocessor $preprocessor,
        ResponsesComposer $responsesComposer
    ) {
        $this->client = $httpClient;
        $this->requestsPreprocessor = $preprocessor;
        $this->responsesComposer = $responsesComposer;
    }

    public function execute(ServiceDataCollection $servers): ResponseInterface
    {
        $servicesToRequest = $this->requestsPreprocessor->getProxiesDataToExecute($servers);
        $pool = new Pool(
            $this->client,
            $this->generateRequests($servicesToRequest),
            [
                'concurrency' => self::CONCURRENT_REQUESTS_AMOUNT,
                'fulfilled'   => $this->createSuccessHandler($servicesToRequest),
                'rejected'    => $this->getFailureHandler($servicesToRequest),
            ]
        );
        $pool->promise()->wait();

        return $this->responsesComposer->createResponse($servers);
    }

    private function createSuccessHandler(ServiceDataCollection $servers): callable
    {
        return function (ResponseInterface $serviceResponse, int $index) use ($servers) {
            /* @var ServiceDataCollection $servers */
            $servers->get($index)->setResponse($serviceResponse);
        };
    }

    private function getFailureHandler(ServiceDataCollection $servers): callable
    {
        return function (BadResponseException $reason, int $index) use ($servers) {
            $serviceId = $servers->get($index)->getService()->getId();
            $response = $this->getResponseFromReasonOrDefault($reason, $serviceId);
            $response = $this->writeErrorToResponse($reason, $response);
            $response->withAddedHeader(
                'X-Microfrontend-Error', [
                    sprintf(
                        'app:%s;error=%s',
                        $serviceId->asString(),
                        urlencode($reason->getMessage())
                    ),
                ]
            );
            $servers->get($index)->setResponse($response);
        };
    }

    private function generateRequests(ServiceDataCollection $proxyData): \Iterator
    {
        /* @var \Mcustiel\MicrofrontendsComposer\ServiceExecutionData $proxyData */
        foreach ($proxyData as $proxiedService) {
            yield $proxiedService->getRequest();
        }
    }

    private function getResponseFromReasonOrDefault(BadResponseException $reason, ServiceId $serviceId): ResponseInterface
    {
        $response = $reason->getResponse();
        if (!$response) {
            return new Response(
                new StringStream(
                    sprintf('%s: %s', $serviceId->asString(), $reason->getMessage()))
            );
        }

        return $response;
    }

    private function writeErrorToResponse(
        BadResponseException $reason,
        ResponseInterface $response
    ): ResponseInterface {
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 400 && $statusCode !== 404) {
            $response = $response->withBody(
                new StringStream(
                    sprintf('<section class="request-error">%s</section>', $response->getBody())
                )
            );
        }

        return $response;
    }
}
