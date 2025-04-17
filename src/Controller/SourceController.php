<?php

namespace App\Controller;

use RuntimeException;
use App\Entity\Source;
use App\DTO\UserSourceDTO;
use App\Repository\SourceRepository;
use App\UseCase\ManageSourceUseCase;
use App\Repository\UserSourceRepository;
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
        $data = $request->request->all();

        try {
            $this->manageSourceUseCase->addSource($data['url'], $data['name'], $user);
            $this->addFlash('success', "Source ajoutée avec succès !");
        } catch (RuntimeException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('sources');
    }

    #[Route('/source/edit/{id}', name: 'source_edit', methods: ['POST'])]
    public function edit(Request $request, UserSourceRepository $userSourceRepository, int $id): Response
    {
        $userSource = $userSourceRepository->find($id);
        
        if (!$userSource) {
            throw $this->createNotFoundException("Source introuvable !");
        }
    
        $newName = $request->request->get('name');
    
        try {
            $this->manageSourceUseCase->updateSourceName($userSource->getSource(), $newName);
            $this->addFlash('success', "Nom mis à jour avec succès !");
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }
    
        return $this->redirectToRoute('sources');
    } 
}
