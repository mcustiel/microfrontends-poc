<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Transformations;

use DOMNode;
use DOMXPath;
use Templado\Engine\Selector;
use Templado\Engine\Transformation;
use Templado\Engine\XPathSelector;

final class ErrorsInserter implements Transformation
{
    /** @var DOMXPath */
    private $xpath;

    public function __construct(DOMXpath $xpath)
    {
        $this->xpath = $xpath;
    }

    public function apply(DOMNode $context): void
    {
        $microFrontendError = $this->xpath->query('//section[@class="request-error"]');

        foreach ($microFrontendError as $errorSection) {
            $node = $context->ownerDocument->importNode($errorSection, true);
            $context->appendChild($node);
        }
    }

    public function getSelector(): Selector
    {
        return new XPathSelector('//section[@id="errors"]');
    }
}
