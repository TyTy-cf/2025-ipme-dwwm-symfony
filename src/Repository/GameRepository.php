<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findByBestSeller(int $limit = null): array
    {
        // SELECT * FROM game g
        $qb = $this->createQueryBuilder('g')
            // JOIN user_own_game oBu ON obu.game_id = g.id
            ->join('g.ownedByUser', 'oBu')
            // GROUP BY g.id
            ->groupBy('g.name')
            // ORDER BY COUNT(g.id) DESC
            ->orderBy('COUNT(g.id)', 'DESC')
            // On ne peut avoir QU'UN SEUL orderBy mais plusieurs addOrderBy
            ->addOrderBy('g.name', 'ASC');

        if ($limit !== null) {
            // LIMIT $limit
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function getMostPlayedGames(int $limit = null): array
    {
        $qb = $this->createQueryBuilder('g')
            ->join('g.ownedByUser', 'oBu')
            ->groupBy('g.name')
            ->orderBy('SUM(oBu.gameTime)', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByCategory(Category $category, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('g')
            ->join('g.categories', 'c')
            ->where('c = :categ')
            ->setParameter('categ', $category);

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function getAll(): QueryBuilder
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.id', 'ASC');
    }

}
