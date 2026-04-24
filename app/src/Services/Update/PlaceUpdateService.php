<?php

namespace App\Services\Update;

use App\Clients\QuickTicketsClient;
use App\Entity\Place;
use App\Parsers\QuickTicketsParsers\DomParser;
use App\Parsers\QuickTicketsParsers\PerformanceParser;
use App\Parsers\QuickTicketsParsers\PlaceParser;
use App\Repository\PlaceRepository;
use App\Services\Import\PerformanceImportService;
use App\Services\PlaceService;

class PlaceUpdateService
{
    public function __construct(
        private QuickTicketsClient $client,
        private DomParser $domParser,
        private PlaceParser $placeParser,
        private PerformanceUpdateService $performanceUpdateService

    ) {
    }

    public function update(Place $place): void
    {
        $html = $this->client->getPageByUrl($place->getUrl());
        $dom = $this->domParser->parse($html);

        $name = $this->placeParser->getName($dom);

        $place->setName($name);

        $this->performanceUpdateService->update($dom, $place);
    }
}
