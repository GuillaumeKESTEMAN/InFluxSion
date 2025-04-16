<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends abstractController
{
    #[Route('/register', name: 'register')]
    public function register(){
        return $this->render('sources/register.html.twig');
    }
}