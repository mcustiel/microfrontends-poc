<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Transformations;

use DOMNode;
use DOMXPath;
use Templado\Engine\Selector;
use Templado\Engine\Transformation;
use Templado\Engine\XPathSelector;

class SectionsReplacer implements Transformation
{
    /** @var DOMXPath */
    private $xpath;

    public function __construct(DOMXpath $xpath)
    {
        $this->xpath = $xpath;
    }

    public function apply(DOMNode $htmlSection): void
    {
        $microFrontendSections = $this->xpath->query('//section');
        foreach ($microFrontendSections as $section) {
            if ($this->sectionsHaveSameId($htmlSection, $section)) {
                $node = $htmlSection->ownerDocument->importNode($section, true);

                //var_dump($htmlSection->ownerDocument->saveHTML($htmlSection));
                //var_dump($node->ownerDocument->saveHTML($node));
                $htmlSection->parentNode->replaceChild($node, $htmlSection);
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
