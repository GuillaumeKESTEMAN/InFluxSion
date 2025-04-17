<?php

namespace App\Controller;

use App\DTO\UserSourceDTO;
use App\Repository\SourceRepository;
use App\Repository\UserSourceRepository;
use App\Service\ArticleService;
use App\UseCase\ManageSourceUseCase;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends AbstractController
{
    public function __construct(private ManageSourceUseCase $manageSourceUseCase)
    {
    }

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

    #[Route('/source/delete/{id}', name: 'source_delete', methods: ['POST'])]
    public function deleteUserSource(int $id): Response
    {
        try {
            $this->manageSourceUseCase->deleteUserSource($id);
            $this->addFlash('success', "Source supprimée de votre liste !");
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('sources');
    }
    
    #[Route('/sources/{id}/articles', name: 'save_articles', methods: ['GET'])]
    public function saveArticles(
        int              $id,
        SourceRepository $sourceRepository,
        ArticleService   $articleService
    ): Response
    {
        $source = $sourceRepository->find($id);

        if (!$source) {
            return $this->json(['error' => 'Source not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($articleService->fetchAndSaveArticlesFromRss($source), Response::HTTP_OK);
    }
}
