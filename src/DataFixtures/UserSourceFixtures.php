<?php

namespace App\DataFixtures;

use App\Entity\Source;
use App\Entity\User;
use App\Entity\UserSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserSourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $user = $this->getReference("user_$i", User::class);

            for ($j = 1; $j <= 2; $j++) {
                $source = $this->getReference("source_$j", Source::class);

                $userSource = new UserSource();
                $userSource->setName("UserSource for user$i and source$j")
                    ->setUser($user)
                    ->setSource($source);

                $manager->persist($userSource);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, SourceFixtures::class];
    }
}