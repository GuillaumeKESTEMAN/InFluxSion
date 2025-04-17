<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\UserArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserArticleController extends AbstractController
{
    #[Route('/article/{id}/mark-viewed', name: 'article_mark_viewed', methods: ['POST'])]
    public function markAsViewed(Article $article, UserArticleRepository $userArticleRepository) :Response
    {
        $user = $this->getUser();

        $userArticleRepository->markAsViewed($user, $article);
        return new Response('article marked as viewed', Response::HTTP_NO_CONTENT);
    }
}