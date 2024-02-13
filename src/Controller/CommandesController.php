<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes')]
    public function index(): Response
    {
        return $this->render('commandes/index.html.twig', [
            'controller_name' => 'CommandesController',
        ]);
    }

    #[Route('/commandes/voirpanier', name: 'app_commandes_panier')]
    public function voirpanier(): Response
    {
        return $this->render('commandes/voirpanier.html.twig');
    }
    #[Route('/commandes/profil', name: 'app_profil')]
    public function profil(): Response
    {
        return $this->render('commandes/profil.html.twig');
    }
}
