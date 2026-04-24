<?php

namespace App\Repository;

use App\Entity\Performance;
use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function getByPerformanceAndTimestamp(Performance $performance, int $timestamp): ?Session
    {
        $date = (new \DateTimeImmutable())->setTimestamp($timestamp);

        return $this->findOneBy([
            'performance' => $performance,
            'time' => $date,
        ]);
    }
}
