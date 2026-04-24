<?php

namespace App\Services\Import;

use App\Entity\MessengerUser;
use App\Entity\Place;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuickTicketsImportService
{
    public function __construct(
        private readonly PlaceRepository $placeRepository,
        private readonly PlaceImportService $placeImportService,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function import(string $url, MessengerUser $user): Place
    {
        $place = $this->placeRepository->findOneByUrl($url);

        if (!$place) {
            $place = $this->placeImportService->importFromUrl($url);
        }

        $user->addPlace($place);
        $this->em->flush();

        return $place;
    }
}
