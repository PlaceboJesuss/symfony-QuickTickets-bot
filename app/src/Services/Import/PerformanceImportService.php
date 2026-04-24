<?php

namespace App\Services\Import;

use App\Entity\Performance;
use App\Entity\Place;
use App\Parsers\QuickTicketsParsers\PerformanceParser;
use App\Services\PerformanceService;
use simple_html_dom\simple_html_dom_node;

class PerformanceImportService
{
    public function __construct(
        private PerformanceParser $parser,
        private PerformanceService $service,
        private SessionImportService $sessionImportService,
    ) {
    }

    public function importFromDom(simple_html_dom_node $dom, Place $place): array
    {
        $performanceNodes = $this->parser->getPerformances($dom);

        return array_map(
            function ($performanceNode) use ($place) {
                $name = $this->parser->getPerformanceName($performanceNode);
                $performance = $this->service->create((new Performance())->setName($name)->setPlace($place));

                $this->sessionImportService->importFromDom($performanceNode, $performance);

                return $performance;
            },
            $performanceNodes
        );
    }
}
