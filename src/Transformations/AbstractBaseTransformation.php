<?php

declare(strict_types=1);

namespace Mcustiel\MicrofrontendsComposer\Transformations;

use DOMElement;
use DOMXPath;
use Templado\Engine\Transformation;

abstract class AbstractBaseTransformation implements Transformation
{
    /** @var DOMXPath */
    protected DOMXPath $xpath;

    protected string $urlPrefix;

    public function __construct(DOMXpath $xpath, string $urlPrefix = '')
    {
        $this->xpath = $xpath;
        $this->urlPrefix = $urlPrefix;
    }

    protected function replaceAttribute(DOMElement $element, string $attribute = 'href'): void
    {
        $href = $element->getAttribute($attribute);

        $parsedUrl = parse_url($href);

        if(isset($parsedUrl['host']) || !isset($parsedUrl['path'])){
            return;
        }

        $parsedPath = $parsedUrl['path'];
        $pathParts = array_filter(explode('/', $parsedPath));

        $first = array_shift($pathParts);
        if ($this->urlPrefix && $first && ($first !== $this->urlPrefix && $first !== '/' . $this->urlPrefix) ) {
            $element->setAttribute($attribute, str_replace('//', '/', '/' . $this->urlPrefix . '/' . $href));
        }
    }
}
