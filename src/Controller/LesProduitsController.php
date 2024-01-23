<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LesProduitsController extends AbstractController
{
    #[Route('/lesProduits', name: 'app_les_produits')]
    public function index(): Response
    {
        return $this->render('les_produits/index.html.twig', [
            'controller_name' => 'LesProduitsController',
        ]);
    }
}
