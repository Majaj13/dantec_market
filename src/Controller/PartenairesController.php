<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartenairesController extends AbstractController
{
    #[Route('/partenaires', name: 'app_partenaires')]
    public function index(): Response
    {
        return $this->render('partenaires/index.html.twig', [
            'controller_name' => 'PartenairesController',
        ]);
    }
    #[Route('/partenaires/getListe', name: 'app_partenaires_liste')]
    public function getListe(): Response
    {
        return $this->render('partenaires/liste.html.twig');
    }
}
