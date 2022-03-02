<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Processors;

use DOMDocument;
use DOMXPath;
use Mcustiel\MicrofrontendsComposer\ServiceExecutionData;
use Mcustiel\MicrofrontendsComposer\Transformations\ErrorsInserter;
use Mcustiel\MicrofrontendsComposer\Transformations\ScriptsInserter;
use Mcustiel\MicrofrontendsComposer\Transformations\SectionsReplacer;
use Mcustiel\MicrofrontendsComposer\Transformations\StylesInserter;
use Templado\Engine\Html;

final class BodyComposer
{
    /** @var DOMXPath[] */
    private array $xPathsCache;

    public function __construct()
    {
        $this->xPathsCache = [];
    }

    public function process(ServiceExecutionData $proxyData, Html $htmlTemplate): void
    {
        $body = (string)$proxyData->getResponse()->getBody();
        $serviceId = $proxyData->getService()->getId();
        if ($body) {
            $xpath = $this->getXpathForService($serviceId->asString(), $body);
            $htmlTemplate->applyTransformation(new StylesInserter($xpath, $proxyData->getService()->getUrlPrefix()));
            $htmlTemplate->applyTransformation(new ScriptsInserter($xpath, $proxyData->getService()->getUrlPrefix()));
            $htmlTemplate->applyTransformation(new SectionsReplacer($xpath, $proxyData->getService()->getUrlPrefix()));
            $htmlTemplate->applyTransformation(new ErrorsInserter($xpath));
        }
    }

    private function getXpathForService(string $serviceId, string $body): DOMXPath
    {
        if (!isset($this->xPathsCache[$serviceId])) {
            $xml = new DOMDocument();
            $xml->recover = true;
            $xml->loadXML($body);
            $this->xPathsCache[$serviceId] = new DOMXpath($xml);
        }

        return $this->xPathsCache[$serviceId];
    }
}
