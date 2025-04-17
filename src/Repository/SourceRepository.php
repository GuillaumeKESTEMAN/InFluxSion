<?php

namespace App\Repository;

use App\Entity\Source;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Source>
 */
class SourceRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Source::class);
        $this->entityManager = $entityManager;
    }

    public function findByUrl(string $url): ?Source
    {
        return $this->createQueryBuilder('s')
            ->where('s.url = :url')
            ->setParameter('url', $url)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(Source $source): void
    {
        $this->entityManager->persist($source);
        $this->entityManager->flush();
    }
}
