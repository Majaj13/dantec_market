<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieParentController extends AbstractController
{
    #[Route('/categorie/parent', name: 'app_categorie_parent')]
    public function index(): Response
    {
        return $this->render('categorie_parent/index.html.twig', [
            'controller_name' => 'CategorieParentController',
        ]);
    }
}
