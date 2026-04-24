<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Place>
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }

    public function findOneById(int $id): ?Place
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findOneByUrl(string $url): ?Place
    {
        return $this->findOneBy(['url' => $url]);
    }
}
