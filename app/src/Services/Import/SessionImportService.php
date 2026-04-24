<?php

namespace App\Services\Import;

use App\Entity\Performance;
use App\Entity\Session;
use App\Parsers\QuickTicketsParsers\SessionParser;
use App\Services\SessionService;
use simple_html_dom\simple_html_dom_node;

class SessionImportService
{
    public function __construct(
        private SessionParser $parser,
        private SessionService $service
    ) {
    }

    public function importFromDom(simple_html_dom_node $dom, Performance $performance): array
    {
        $sessionNodes = $this->parser->getPerformanceSessions($dom);

        $sessions = array_map(function ($sessionNode) use ($performance) {
            try {
                $soldOut = $this->parser->getSessionSoldOut($sessionNode);
                $timestamp = $this->parser->getSessionTimestamp($sessionNode);

                return $this->service->create(
                    (new Session())
                        ->setPerformance($performance)
                        ->setIsSoldOut($soldOut)
                        ->setTimestamp($timestamp)
                );
            } catch (\Exception $e) {
                return null;
            }
        }, $sessionNodes);

        return array_filter($sessions);
    }
}
