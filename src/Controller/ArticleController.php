<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'articles', methods: ['GET'])]
    public function index(
        ArticleRepository $articleRepository,
        UserArticleRepository $userArticleRepository
    ): Response
    {
        $latestArticles = $articleRepository->findLatestArticles(10);

        $viewedArticleIds = $userArticleRepository
            ->findViewedArticleIdsByUserAndArticles($this->getUser(), $latestArticles);

        return $this->render('index.html.twig', [
            'articles' => $latestArticles,
            'viewedArticleIds' => $viewedArticleIds,
        ]);
    }


}

