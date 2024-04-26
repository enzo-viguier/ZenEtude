<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DailyController extends AbstractController
{
    #[Route('/daily', name: 'app_daily')]
    public function index( Request $request): Response
    {

        $completed = false;

        return $this->render('daily/index.html.twig', [
            'completed' => $completed,
        ]);
    }
}
