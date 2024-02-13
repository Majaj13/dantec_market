<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProduitsRepository;
use App\Repository\CategorieParentRepository;
use App\Repository\CommandesRepository;
use App\Entity\User;
use App\Entity\Commandes;
use App\Entity\Commander;
use App\Entity\Categorie;
use App\Repository\ActualiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Utils\Utils;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ApiController extends AbstractController
{

    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    #[Route('/api/mobile/GetFindUser', name: 'app_api_mobile_getuser')]
    public function GetFindUser(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher,)
    {

        $postdata = json_decode($request->getContent());
       
        if (isset($postdata->Nom) && isset($postdata->Prenom) && isset($postdata->Password)) {
            $nom = $postdata->Nom;
            $prenom = $postdata->Prenom;
       
            $password = $postdata->Password;
        } else 
            return  Utils::ErrorMissingArgumentsDebug($request->getContent());
        $user = $userRepository->findOneBy( ['prenom' => $prenom,'nom' => $nom]);
  
        if (!$user || !$userPasswordHasher->isPasswordValid($user, $password)) {
            return Utils::ErrorInvalidCredentials();
        }

        $response = new Utils;
        return $response->GetJsonResponse($request, $user);
    }
    #[Route('/api/mobile/GetListeProduitParCategorie', name: 'app_api_mobile_GetListeProduitParCategorie')]
    public function GetListeProduitParCategorie(Request $request, ProduitsRepository $produitsRepository)
    {
        $postdata = json_decode($request->getContent());
        $var =  $produitsRepository->getProduitInfoByCategorie($postdata->categoryID);
       
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    #[Route('/api/mobile/categories', name: 'app_api_mobile_getCategories')]
    public function getCategories(Request $request,CategorieParentRepository $categorieparentRepository)
    {
        $var =  $categorieparentRepository->findAllWithCategories();
        
        $response = new Utils;
        $tab = ["lesProduits","lacategorieParent"];
    return $response->GetJsonResponse($request, $var,$tab);
    }

    #[Route('/api/mobile/getProduit', name: 'app_api_mobile_getProduit')]
    public function getProduit(Request $request,ProduitsRepository $produitsRepository)
    {
        $postdata = json_decode($request->getContent());
       
        $var =  $produitsRepository->find($postdata->id);
       
        $response = new Utils;
        $tab = ["lesCommandes","leProduit","lesProduits","lacategorieParent","leUser"];
    return $response->GetJsonResponse($request, $var,$tab);
    }

    #[Route('/api/mobile/GetPromo', name: 'app_api_mobile_GetPromo')]
    public function GetPromo(Request $request, ProduitsRepository $produitsRepository)
    {
        $postdata = json_decode($request->getContent());
        $var =  $produitsRepository->getProduitPromo($postdata->id);
       
        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }

    #[Route('/api/mobile/AjoutProduitCommande', name: 'app_api_mobile_AjoutProduitCommande')]
    public function AjoutProduitCommande(Request $request, EntityManagerInterface $manager, CommandesRepository $commandesRepository, ProduitsRepository $produitsRepository): Response
    {
        $postdata = json_decode($request->getContent());
        $user = $this->getUser();
    
        if ($user) {
            $commande = $commandesRepository->findDerniereNonValideeByUser($user);
    
            if (!$commande) {
                $commande = new Commandes();
                $commande->setLeUser($user);
                $commande->setDateCommande(new \DateTime());
                $manager->persist($commande);
            }
    
            if (isset($postdata->produitId) && isset($postdata->quantite)) {
                $produit = $produitsRepository->find($postdata->produitId);
                if ($produit) {
                    // Vérifier si le produit est déjà dans la commande
                    $ligneCommandeExistante = null;
                    foreach ($commande->getLesCommandes() as $ligneCommande) {
                        if ($ligneCommande->getLeProduit()->getId() === $produit->getId()) {
                            $ligneCommandeExistante = $ligneCommande;
                            break;
                        }
                    }
    
                    if ($ligneCommandeExistante) {
                        // Produit déjà dans la commande, incrémenter la quantité
                        $quantiteActuelle = $ligneCommandeExistante->getQuantite();
                        $ligneCommandeExistante->setQuantite($quantiteActuelle + $postdata->quantite);
                    } else {
                        // Produit pas encore dans la commande, l'ajouter
                        $commander = new Commander();
                        $commander->setLeProduit($produit);
                        $commander->setQuantite($postdata->quantite);
                        $commander->setLaCommande($commande);
                        $manager->persist($commander);
                    }
                } else {
                    return new Response("Produit non trouvé", Response::HTTP_NOT_FOUND);
                }
            } else {
                return new Response("Données manquantes", Response::HTTP_BAD_REQUEST);
            }
    
            $manager->flush();
            return new Response("Produit ajouté/mis à jour dans la commande", Response::HTTP_OK);
        }
    
        return new Response("Utilisateur non authentifié", Response::HTTP_FORBIDDEN);
    }

    #[Route('/api/mobile/getLesActualites', name: 'app_api_mobile_getLesActualites')]
    public function getActualites(Request $request, ActualiteRepository $actualiteRepository)
    {
        $actualites = $actualiteRepository->findAll();
   
        $response = new Utils;
        $tab = ["titre","texte","lesImages"];
        return $response->GetJsonResponse($request, $actualites, $tab);

    }
    

}
