<?php

namespace Mcustiel\MicrofrontendsComposer\Processors;

use Mcustiel\MicrofrontendsComposer\Collections\ServiceDataCollection;
use Mcustiel\MicrofrontendsComposer\HtmlTemplateLoader;
use Mcustiel\MicrofrontendsComposer\StringStream;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

class ResponsesComposer
{
    /** @var HeadersComposer */
    private $headersComposer;

    /** @var BodyComposer */
    private $bodyComposer;

    /** @var HtmlTemplateLoader */
    private $templateLoader;

    public function __construct(
        BodyComposer $bodyComposer,
        HeadersComposer $headersComposer,
        HtmlTemplateLoader $templateLoader
    ) {
        $this->templateLoader = $templateLoader;
        $this->bodyComposer = $bodyComposer;
        $this->headersComposer = $headersComposer;
    }

    public function createResponse(ServiceDataCollection $servers): ResponseInterface
    {
        $htmlTemplate = $this->templateLoader->load();
        $response = new Response();
        foreach ($servers as $server) {
            $this->bodyComposer->process(
                $server,
                $htmlTemplate
            );
            $response = $this->headersComposer->process(
                $server,
                $response
            );
        }

        return $response->withBody(new StringStream($htmlTemplate->asString()));
    }
}
