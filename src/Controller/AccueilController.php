<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil')]
    public function index(): Response
    {
        return $this->render('accueil.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
    #[Route('/403', name: '403')]
    public function accessDenied(): Response
    {
        return $this->render('accessdenied/403.html.twig');
    }
    #[Route('/404', name: '404')]
    public function notFound(): Response
    {
        return $this->render('accessdenied/404.html.twig');
    }
}
