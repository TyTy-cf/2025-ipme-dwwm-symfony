<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findByMostPlayed(int $limit = null): array
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.games', 'g')
            ->join('g.ownedByUser', 'uog')
            ->orderBy('SUM(uog.gameTime)', 'DESC')
            ->groupBy('c.id');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findFullBySlug(string $slug): ?Category
    {
        return $this->createQueryBuilder('c') // SELECT c.* FROM category c
            ->select('c', 'g') // SELECT c.*, g.*
            ->join('c.games', 'g') // JOIN game_category + JOIN game g
            ->where('c.slug = :slug') // WHERE c.slug = ?
            ->setParameter('slug', $slug) // ADD PARAMETER 0, $slug
            ->orderBy('g.price', 'DESC') // ORDER BY g.price DESC
            ->getQuery() // MET EN FORME LA REQUETE POUR L'EXECUTER
            ->getOneOrNullResult(); // EXECUTE LA REQUETE (Le oneOrNullResult fait secrètement un LIMIT 1)
    }

    public function getFullBySlug(string $slug): ?QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'g')
            ->join('c.games', 'g')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('g.price', 'DESC')
        ;
    }
}
