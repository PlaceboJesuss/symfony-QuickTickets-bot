<?php

namespace App\Services;

use App\Entity\Performance;
use App\Entity\Place;
use App\Parsers\QuickTicketsParsers\PerformanceParser;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use simple_html_dom\simple_html_dom_node;

class PerformanceService
{
    public function __construct(
        private PerformanceRepository $repo,
        private EntityManagerInterface $em

    ) {
    }

    public function getOrCreate(Place $place, string $name): Performance
    {
        $performance = $this->repo->findOneByPlaceAndName($place, $name);

        if ($performance) {
            return $performance;
        }

        return $this->create(
            (new Performance())
                ->setPlace($place)
                ->setName($name)
        );
    }

    public function create(Performance $performance): Performance
    {
        $this->em->persist($performance);
        $performance->getPlace()->addPerformance($performance);

        return $performance;
    }
}
