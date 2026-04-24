<?php

namespace App\Services\Update;

use App\Entity\Performance;
use App\Entity\Place;
use App\Parsers\QuickTicketsParsers\PerformanceParser;
use App\Repository\PerformanceRepository;
use App\Services\Import\SessionImportService;
use App\Services\PerformanceService;
use simple_html_dom\simple_html_dom_node;

class PerformanceUpdateService
{
    public function __construct(
        private readonly PerformanceParser $parser,
        private readonly PerformanceService $service,
        private readonly PerformanceRepository $repository,
        private readonly SessionUpdateService $sessionUpdateService,
    )
    {
    }

    public function update(simple_html_dom_node $dom, Place $place): array
    {
        $performanceNodes = $this->parser->getPerformances($dom);

        return array_map(
            function ($performanceNode) use ($place) {
                $name = $this->parser->getPerformanceName($performanceNode);

                $performance = $this->repository->findOneByPlaceAndName($place, $name);

                if (!$performance) {
                    $performance = $this->service->create((new Performance())->setName($name)->setPlace($place));
                }

                $this->sessionUpdateService->update($performanceNode, $performance, $place);

                return $performance;
            },
            $performanceNodes
        );
    }
}
