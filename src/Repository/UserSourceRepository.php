<?php

namespace App\Repository;

use App\Entity\UserSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<UserSource>
 */
class UserSourceRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, UserSource::class);
        $this->entityManager = $entityManager;
    }

    public function save(UserSource $userSource): void
    {
        $this->entityManager->persist($userSource);
        $this->entityManager->flush();
    }
}
