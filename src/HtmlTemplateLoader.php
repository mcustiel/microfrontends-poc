<?php

namespace Mcustiel\MicrofrontendsComposer;

use Templado\Engine\FileName;
use Templado\Engine\Html;
use Templado\Engine\Templado;

class HtmlTemplateLoader
{
    // This should depend on the request if there are several templates
    public function load(): Html
    {
        return Templado::loadHtmlFile(new FileName(__DIR__ . '/../templates/index.xhtml'));
    }
}
