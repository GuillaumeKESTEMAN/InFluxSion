<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SourceController extends AbstractController
{
    #[Route('/sources', name: 'sources', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('sources/index.html.twig');
    }
}
