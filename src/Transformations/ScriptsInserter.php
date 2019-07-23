<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Transformations;

use DOMNode;
use DOMXPath;
use Templado\Engine\Selector;
use Templado\Engine\Transformation;
use Templado\Engine\XPathSelector;

final class ScriptsInserter implements Transformation
{
    /** @var DOMXPath */
    private $xpath;

    public function __construct(DOMXpath $xpath)
    {
        $this->xpath = $xpath;
    }

    public function apply(DOMNode $htmlHead): void
    {
        $microFrontendScripts = $this->xpath->query('//script');
        foreach ($microFrontendScripts as $script) {
            $node = $htmlHead->ownerDocument->importNode($script, true);
            $htmlHead->appendChild($node);
        }
    }

    public function getSelector(): Selector
    {
        return new XPathSelector('//body');
    }
}
