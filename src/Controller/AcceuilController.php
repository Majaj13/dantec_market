<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitsRepository;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'app_acceuil')]
    public function index(ProduitsRepository $produitRepository): Response
    {
        $topVendus = $produitRepository->findTopVendusLastWeek();

        dd($topVendus);

        return $this->render('acceuil/index.html.twig', [
            'controller_name' => 'AcceuilController',
        ]);
    }
}
