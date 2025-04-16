<?php

namespace App\Controller;

use App\Entity\Source;
use App\Entity\UserSource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SourceController extends AbstractController
{
    #[Route('/sources', name: 'sources', methods: ['GET'])]
    public function list(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $userSources = $user->getUserSources()->map(fn($userSource) => [
            'id' => $userSource->getId(),
            'url' => $userSource->getSource()->getUrl(),
            'name' => $userSource->getName()
        ])->toArray();

        return $this->render('sources/index.html.twig', [
            'sources' => $userSources
        ]);
    }

    #[Route('/source/add', name: 'source_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $data = $request->request->all();
        
        if (empty($data['url']) || !self::isValidRSS($data['url'])) {
            $this->addFlash('error', 'L\'URL fournie est invalide.');
            return $this->redirectToRoute('sources');
        }

        $url = htmlspecialchars(strip_tags($data['url']));
        $name = htmlspecialchars(strip_tags($data['name']));

        $existingSource = $entityManager->getRepository(Source::class)->findOneBy(['url' => $url]);
        if ($existingSource) {
            $this->addFlash('error', 'Cette source existe déjà.');
            return $this->redirectToRoute('sources');
        }

        $source = new Source();
        $source->setUrl($url);

        $entityManager->persist($source);
        $entityManager->flush();

        $userSource = new UserSource();
        $userSource->setUser($user);
        $userSource->setSource($source);
        $userSource->setName($name);

        $entityManager->persist($userSource);
        $entityManager->flush();

        $this->addFlash('success', 'Source ajoutée avec succès !');
        return $this->redirectToRoute('sources');
    }

    private static function isValidRSS(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $rss = @simplexml_load_file($url);
        return $rss && isset($rss->channel);
    }
}
