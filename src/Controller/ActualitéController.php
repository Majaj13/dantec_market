<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Actualite;

class ActualitéController extends AbstractController
{
    #[Route('/actualite', name: 'app_actualite')]
    public function index(): Response
    {
        return $this->render('actualité/index.html.twig', [
            'controller_name' => 'ActualitéController',
        ]);
    }

    #[Route('/actualite/voiractu/{id}', name: 'app_voir_actu')]
    public function voirleproduit(Actualite $laActualite ): Response
    {
        return $this->render('actualité/pageActu.html.twig', ['actu' => $laActualite->getId()
           
        ]);
    }
}
