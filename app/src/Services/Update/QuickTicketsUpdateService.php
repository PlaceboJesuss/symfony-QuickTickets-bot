<?php

namespace App\Services\Update;

use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuickTicketsUpdateService
{

    public function __construct(
        private PlaceRepository $placeRepository,
        private PlaceUpdateService $placeUpdateService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function update()
    {
        $places = $this->placeRepository->findAll();

        foreach ($places as $place) {
            $this->placeUpdateService->update($place);
        }

        $this->entityManager->flush();
    }
}
