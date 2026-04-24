<?php

namespace App\Parsers\QuickTicketsParsers;

use simple_html_dom\simple_html_dom_node as SimpleHtmlDomNode;

class PerformanceParser
{
    public function getPerformances(SimpleHtmlDomNode $dom): array
    {
        return $dom->find('.body #elems-list .elem');
    }

    public function getPerformanceName(SimpleHtmlDomNode $performance): ?string
    {
        return $performance->find('.c h3 a .underline', 0)?->innertext;
    }

    public function getPerformanceImage(SimpleHtmlDomNode $performance): ?string
    {
        return $performance->find('a img.polaroid', 0)?->src;
    }
}
