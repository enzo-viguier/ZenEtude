<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccessController extends AbstractController
{
    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(): Response
    {
        return $this->render('access/connexion.html.twig');
    }

    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(): Response
    {
        return $this->render('access/inscription.html.twig');
    }

    #[Route('/deconnexion', name: 'app_adeconnexion')]
    public function deconnexion(): Response
    {

        return $this->redirectToRoute('app_home');

    }

}
