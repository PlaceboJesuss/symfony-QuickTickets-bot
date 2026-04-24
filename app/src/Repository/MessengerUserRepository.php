<?php

namespace App\Repository;

use App\Entity\Messenger;
use App\Entity\MessengerUser;
use App\Enums\MessengerTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessengerUser>
 */
class MessengerUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessengerUser::class);
    }

    //    /**
    //     * @return MessengerUser[] Returns an array of MessengerUser objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function getOrCreate(int $chatId, Messenger $messenger, ?string $username): MessengerUser
    {
        $user = $this->findOneBy([
            'chatId' => $chatId,
            'messenger' => $messenger,
        ]);

        if ($user !== null) {
            return $user;
        }

        $user = (new MessengerUser())
            ->setChatId($chatId)
            ->setMessenger($messenger)
            ->setUsername($username);

        $this->getEntityManager()->persist($user);



        $this->getEntityManager()->flush();

        return $user;
    }

    public function findOneByChatAndMessenger(int $chatId, Messenger $messenger): ?MessengerUser
    {
        return $this->findOneBy([
            'chatId' => $chatId,
            'messenger' => $messenger,
        ]);
    }
}
