<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    
    /**
     * @param int $limit Number of articles to get
     * @return Article[] Return array of articles
     */
    public function findLatestArticles( array $userSourcesIds, int $limit = 20): array
    {
        return $this->createQueryBuilder('article')
            ->setParameter('userSourcesId', $userSourcesIds)
            ->where('article.source IN (:userSourcesId)')
            ->orderBy('article.date', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}

