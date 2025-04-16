<?php

namespace App\Controller;

use RuntimeException;
use App\DTO\UserSourceDTO;
use App\UseCase\ManageSourceUseCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SourceController extends AbstractController
{
    public function __construct(private ManageSourceUseCase $manageSourceUseCase) {}

    #[Route('/sources', name: 'sources', methods: ['GET'])]
    public function list(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        $userSources = $user->getUserSources()->map(fn($userSource) => new UserSourceDTO(
            $userSource->getId(),
            $userSource->getSource()->getUrl(),
            $userSource->getName()
        ))->toArray();
    
        return $this->render('sources/index.html.twig', [
            'sources' => $userSources
        ]);
    }

    #[Route('/source/add', name: 'source_add', methods: ['POST'])]
    public function add(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $data = $request->request->all();

        try {
            $this->manageSourceUseCase->addSource($data['url'], $data['name'], $user);
            $this->addFlash('success', "Source ajoutée avec succès !");
        } catch (RuntimeException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('sources');
    }
}
