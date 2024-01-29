<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitsRepository;
use App\Repository\CommentairesRepository;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'app_acceuil')]
    public function index(ProduitsRepository $produitRepository,CommentairesRepository $commentaireRepository): Response
    {
        $topVendus = $produitRepository->findTopVendusLastWeek();

        $latestComments = $commentaireRepository->findLatestComments();

        return $this->render('acceuil/index.html.twig', [
            'topventes' => $topVendus,'dernierscommentaires' =>  $latestComments,
        ]);
    }
}
