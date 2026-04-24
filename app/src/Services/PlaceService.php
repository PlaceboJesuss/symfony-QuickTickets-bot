<?php

namespace App\Services;

use App\Clients\QuickTicketsClient;
use App\Entity\Place;
use App\Parsers\QuickTicketsParsers\DomParser;
use App\Parsers\QuickTicketsParsers\PlaceParser;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use simple_html_dom\simple_html_dom_node;

class PlaceService
{
    public function __construct(
        private PlaceRepository $repo,
        private EntityManagerInterface $em
    ) {
    }

    public function getOrCreate(Place $place): Place
    {
        $place = $this->repo->findOneByUrl($place->getUrl());

        if ($place) {
            return $place;
        }

        return $this->create($place);
    }



    public function create(Place $place): Place
    {
        $this->em->persist($place);

        return $place;
    }
}
