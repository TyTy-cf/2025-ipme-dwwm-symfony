<?php

namespace App\Repository;

use App\Entity\UserOwnGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserOwnGame>
 */
class UserOwnGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnGame::class);
    }

    public function getTotalGameTime(string $name)
    {
        return $this->createQueryBuilder('uOg')
            ->select('SUM(uOg.gameTime)')
            ->join('uOg.user', 'u')
            ->where('u.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
