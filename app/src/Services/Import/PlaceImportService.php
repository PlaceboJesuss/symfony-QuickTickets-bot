<?php

namespace App\Services\Import;

use App\Clients\QuickTicketsClient;
use App\Entity\Place;
use App\Parsers\QuickTicketsParsers\DomParser;
use App\Parsers\QuickTicketsParsers\PerformanceParser;
use App\Parsers\QuickTicketsParsers\PlaceParser;
use App\Services\PlaceService;

class PlaceImportService
{
    public function __construct(
        private QuickTicketsClient $client,
        private DomParser $domParser,
        private PlaceParser $placeParser,
        private PlaceService $placeService,
        private PerformanceImportService $performanceImportService

    ) {
    }

    public function importFromUrl(string $url): Place
    {
        $html = $this->client->getPageByUrl($url);
        $dom = $this->domParser->parse($html);

        $name = $this->placeParser->getName($dom);

        $place = $this->placeService->create(
            (new Place())
                ->setName($name)
                ->setUrl($url)
        );

        $this->performanceImportService->importFromDom($dom, $place);

        return $place;
    }
}
