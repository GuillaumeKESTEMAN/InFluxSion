<?php

namespace App\Controller;

use App\DTO\UserSourceDTO;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'articles', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        $userSourcesIds = $user->getUserSources()->map(function ($userSource) {
            return $userSource->getId();
        })->toArray();

        $latestArticles = $articleRepository->findLatestArticles(10, $userSourcesIds);

        return $this->render('index.html.twig', [
            'articles' => $latestArticles,
        ]);
    }
}

