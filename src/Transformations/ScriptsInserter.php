<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Transformations;

use DOMNode;
use Templado\Engine\Selector;
use Templado\Engine\XPathSelector;

final class ScriptsInserter extends AbstractBaseTransformation
{
    public function apply(DOMNode $context): void
    {
        $microFrontendScripts = $this->xpath->query('//script');
        foreach ($microFrontendScripts as $script) {
            $this->replaceAttribute($script, 'src');
            $node = $context->ownerDocument->importNode($script, true);
            $context->appendChild($node);
        }
    }

    public function getSelector(): Selector
    {
        return new XPathSelector('//body');
    }
}
