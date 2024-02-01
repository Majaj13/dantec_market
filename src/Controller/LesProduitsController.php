<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieParentRepository;
use App\Repository\ProduitsRepository;
use App\Entity\Produits;

class LesProduitsController extends AbstractController
{
    #[Route('/lesProduits', name: 'app_les_produits')]
    public function index( CategorieParentRepository $categorieParentRepository): Response
    {
        $categories = $categorieParentRepository->findAllWithCategories();   
      
        return $this->render('les_produits/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/lesProduits/voirleproduit/{id}', name: 'app_voir_produit')]
    public function voirleproduit(Produits $leproduit ): Response
    {
        return $this->render('les_produits/fiche.html.twig', ['product' => $leproduit->getId()
           
        ]);
    }
    
}
