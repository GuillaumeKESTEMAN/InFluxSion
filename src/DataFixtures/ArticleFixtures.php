<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Source;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $sourceIndex = $i % 2 === 0 ? 1 : 2;
            $source = $this->getReference("source_$sourceIndex", Source::class);
            $article = new Article();
            $article->setTitle("Article $i")
                ->setUrl("https://example.com/article-$i")
                ->setImage("https://picsum.photos/200")
                ->setDescription("Description for article $i")
                ->setDate(new DateTimeImmutable('-' . rand(0, 365) . ' days'))
                ->setSource($source);

            $manager->persist($article);
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [SourceFixtures::class];
    }
}
