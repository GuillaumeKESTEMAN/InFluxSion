<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Source;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Feed\Reader\Reader;

readonly class ArticleService
{
    public function __construct(private EntityManagerInterface $entityManager, private Reader $reader)
    {
    }

    public function fetchAndSaveArticlesFromRss(Source $source): array
    {
        try {
            $rssFeed = $this->reader::import($source->getUrl());
        } catch (\Exception $e) {
            throw new \RuntimeException('Invalid RSS feed: ' . $e->getMessage());
        }

        $articleRepository = $this->entityManager->getRepository(Article::class);

        $rssUrls = [];
        foreach ($rssFeed as $item) {
            $rssUrls[] = (string)$item->getLink();
        }

        $existingUrls = $articleRepository->createQueryBuilder('a')
            ->select('a.url')
            ->where('a.url IN (:urls)')
            ->setParameter('urls', $rssUrls)
            ->getQuery()
            ->getResult();

        $existingUrls = array_column($existingUrls, 'url');

        $articles = [];

        foreach ($rssFeed as $item) {
            $url = (string)$item->getLink();

            if (!in_array($url, $existingUrls, true)) {
                $article = new Article();
                $article->setUrl($url)
                    ->setTitle($item->getTitle())
                    ->setDescription($item->getDescription())
                    ->setImage($item->getEnclosure()?->url)
                    ->setDate($item->getDateCreated())
                    ->setSource($source);

                $articles[] = $article;

                $this->entityManager->persist($article);
            }
        }

        $this->entityManager->flush();

        return $articles;
    }}