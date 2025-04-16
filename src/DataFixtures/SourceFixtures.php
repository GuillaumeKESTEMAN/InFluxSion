<?php

namespace App\DataFixtures;

use App\Entity\Source;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SourceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $source = new Source();
            $source->setUrl("https://source-$i.com");
            $manager->persist($source);

            $this->addReference("source_$i", $source);
        }

        $manager->flush();
    }
}
