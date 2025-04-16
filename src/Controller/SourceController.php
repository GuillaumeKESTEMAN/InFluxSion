<?php

namespace App\Controller;

use App\DTO\UserSourceDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends AbstractController
{
    #[Route('/sources', name: 'sources', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();

        $userSources = $user->getUserSources()->map(function ($userSource) {
            return new UserSourceDTO(
                $userSource->getId(),
                $userSource->getSource()->getUrl(),
                $userSource->getName()
            );
        })->toArray();

        return $this->render('sources/index.html.twig', [
            'sources' => $userSources,
        ]);
    }
}
