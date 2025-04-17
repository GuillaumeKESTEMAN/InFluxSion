<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'articles', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $userSourcesIds = $this->getUser()->getUserSources()->map(fn ($userSource) => $userSource->getId())->toArray();
        $latestArticles = $articleRepository->findLatestArticles($userSourcesIds, 10, );

        return $this->render('index.html.twig', [
            'articles' => $latestArticles,
        ]);
    }
}

