<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\UserArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


class UserArticleRepository extends ServiceEntityRepository
{

    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, UserArticle::class);
        $this->em = $manager;
    }

    public function markAsViewed(User $user, Article $article): void
    {
        $existing = $this->findOneBy([
            'user' => $user,
            'article' => $article,
        ]);

        if ($existing && $existing->getHasViewed()) {
            return;
        }
        if (!$existing) {
            $userArticle = new UserArticle();
            $userArticle->setUser($user);
            $userArticle->setArticle($article);
            $userArticle->setHasViewed(true);
            $this->em->persist($userArticle);
        }else {
            $existing->setHasViewed(true);
        }
        $this->em->flush();
    }

    public function findViewedArticleIdsByUserAndArticles(User $user, array $articles): array
    {
        if (empty($articles)) {
            return [];
        }

        return $this->createQueryBuilder('ua')
            ->select('IDENTITY(ua.article) AS articleId')
            ->where('ua.user = :user')
            ->andWhere('ua.hasViewed = true')
            ->andWhere('ua.article IN (:articles)')
            ->setParameter('user', $user)
            ->setParameter('articles', $articles)
            ->getQuery()
            ->getSingleColumnResult();
    }
}