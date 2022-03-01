<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Transformations;

use DOMNode;
use Templado\Engine\Selector;
use Templado\Engine\XPathSelector;

final class StylesInserter extends AbstractBaseTransformation
{
    public function apply(DOMNode $context): void
    {
        $microFrontendStyles = $this->xpath->query('//style | //link[@rel=\'stylesheet\']');
        foreach ($microFrontendStyles as $style) {
            $this->replaceAttribute($style);
            $node = $context->ownerDocument->importNode($style, true);
            $context->appendChild($node);
        }
    }

    public function getSelector(): Selector
    {
        return new XPathSelector('//head');
    }
}
