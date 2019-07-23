<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Transformations;

use DOMNode;
use DOMXPath;
use Templado\Engine\Selector;
use Templado\Engine\Transformation;
use Templado\Engine\XPathSelector;

final class StylesInserter implements Transformation
{
    /** @var DOMXPath */
    private $xpath;

    public function __construct(DOMXpath $xpath)
    {
        $this->xpath = $xpath;
    }

    public function apply(DOMNode $htmlHead): void
    {
        $microFrontendStyles = $this->xpath->query('//style | //link[@rel=\'stylesheet\']');
        foreach ($microFrontendStyles as $style) {
            $node = $htmlHead->ownerDocument->importNode($style, true);
            $htmlHead->appendChild($node);
        }
    }

    public function getSelector(): Selector
    {
        return new XPathSelector('//head');
    }
}
