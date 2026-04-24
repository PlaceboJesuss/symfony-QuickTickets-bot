<?php

namespace App\Parsers\QuickTicketsParsers;

use simple_html_dom\simple_html_dom_node as SimpleHtmlDomNode;

class PlaceParser
{
    public function getName(SimpleHtmlDomNode $dom): ?string{
        return $dom->find('#organisation .head .info a.title h2', 0)?->innertext;
    }
}
