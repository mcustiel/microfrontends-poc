<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Transformations;

use DOMElement;
use DOMNode;
use Templado\Engine\Selector;
use Templado\Engine\XPathSelector;

class SectionsReplacer extends AbstractBaseTransformation
{

    public function apply(DOMNode $context): void
    {
        $microFrontendSections = $this->xpath->query('//section');
        foreach ($microFrontendSections as $section) {
            if ($this->sectionsHaveSameId($context, $section)) {
                /** @var DOMElement $section */
                $images = $section->getElementsByTagName('img');
                foreach ($images as $img) {
                    $this->replaceAttribute($img, 'src');
                }

                $node = $context->ownerDocument->importNode($section, true);
                $context->parentNode->replaceChild($node, $context);
            }
        }
    }

    public function getSelector(): Selector
    {
        return new XPathSelector('//section');
    }

    private function sectionsHaveSameId(DOMNode $htmlSection, DOMNode $section): bool
    {
        return $section->hasAttributes() && $htmlSection->hasAttributes()
            && $section->attributes->getNamedItem('id') !== null
            && $htmlSection->attributes->getNamedItem('id') !== null
            && $section->attributes->getNamedItem('id')->nodeValue
            === $htmlSection->attributes->getNamedItem('id')->nodeValue;
    }
}
