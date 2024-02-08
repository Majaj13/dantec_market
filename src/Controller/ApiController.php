<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProduitsRepository;
use App\Repository\CategorieParentRepository;
use App\Repository\CommandesRepository;
use App\Repository\PlanningRepository;
use App\Repository\FavorisRepository;
use App\Entity\User;
use App\Entity\Réserver;
use App\Entity\Commandes;
use App\Entity\Commander;
use App\Entity\Categorie;
use App\Entity\Favoris;
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
        $tab = ["lesCommandes","leProduit","lesProduits","lacategorieParent","leUser","lesPromos"];
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

                
            }
    
            if (isset($postdata->produitId) && isset($postdata->quantite)) {
                $produit = $produitsRepository->find($postdata->produitId);
                if ($produit) {
                    // Vérifier si le produit est déjà dans la commande
                    $commander = null;
                    foreach ($commande->getLesCommandes() as $ligneCommande) {
                        if ($ligneCommande->getLeProduit()->getId() === $produit->getId()) {
                            $commander = $ligneCommande;
                            break;
                        }
                    }
    
                    if ($commander) {
                        // Produit déjà dans la commande, incrémenter la quantité
                        $quantiteActuelle = $commander->getQuantite();
                        $MontantTotalActuel = $commande->getMontantTotal();
                        $commander->setQuantite($quantiteActuelle + $postdata->quantite);
                        $commander->setPrixretenu($postdata->prix);
                        $manager->persist($commander);
                        $commande->setMontantTotal($MontantTotalActuel + $postdata->prix);
                        $commande->setValider(false);
                        $manager->persist($commande);
                    } else {
                        // Produit pas encore dans la commande, l'ajouter
                        $commander = new Commander();
                        $MontantTotalActuel = $commande->getMontantTotal();
                        $commander->setLeProduit($produit);
                        $commander->setQuantite($postdata->quantite);
                        $commander->setPrixretenu(floatval($postdata->prix));
                        $commander->setLaCommande($commande);
                        $manager->persist($commander);
                        $commande->setMontantTotal($MontantTotalActuel + $postdata->prix);
                        $commande->setValider(false);
                        $manager->persist($commande);
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

    #[Route('/api/mobile/MajProduitCommande', name: 'app_api_mobile_MajProduitCommande')]
    public function MajProduitCommande(Request $request, EntityManagerInterface $manager, CommandesRepository $commandesRepository, ProduitsRepository $produitsRepository): Response
    {
        $postdata = json_decode($request->getContent());
        $user = $this->getUser();
    
        if ($user) {
            $commande = $commandesRepository->findDerniereNonValideeByUser($user);
   
    
            if (isset($postdata->nomProduit) && isset($postdata->quantite)) {
                $produit = $produitsRepository->findOneBy(['nomProduit' => $postdata->nomProduit]);
                if ($produit) {            
                    // Vérifier si le produit est déjà dans la commande
                    $commander = null;
                    foreach ($commande->getLesCommandes() as $ligneCommande) {
                        if ($ligneCommande->getLeProduit()->getId() === $produit->getId()) {
                            $commander = $ligneCommande;
                            break;
                        }
                    }
    
                    if ($commander) {
                        $quantiteActuelle = $commander->getQuantite();
                        $MontantTotalActuel = $commande->getMontantTotal();
                        
                        // Calculer la différence de quantité
                        $differenceQuantite = $postdata->quantite - $quantiteActuelle;
                    
                        // Si la différence de quantité est positive, cela signifie que la quantité a été augmentée
                        // Si elle est négative, cela signifie que la quantité a été diminuée
                        if ($differenceQuantite > 0) {
                            // Quantité augmentée, incrémenter le montant total
                            $commande->setMontantTotal($MontantTotalActuel + ($commander->getPrixretenu()));
                        } elseif ($differenceQuantite < 0) {
                            // Quantité diminuée, décrémenter le montant total
                            // Assurez-vous de ne pas décrémenter en dessous de 0
                            $nouveauMontant = $MontantTotalActuel - ($commander->getPrixretenu()); // La différenceQuantite est négative
                            $commande->setMontantTotal(max(0, $nouveauMontant));
                        }
                        
                        // Mettre à jour la quantité dans tous les cas
                        $commander->setQuantite($postdata->quantite);
                    
                        $manager->persist($commande);
                        $manager->persist($commander);
                    }
                    
                } else {
                    return new Response("Produit non trouvé", Response::HTTP_NOT_FOUND);
                }
            } else {
                return new Response("Données manquantes", Response::HTTP_BAD_REQUEST);
            }
    
            $manager->flush();
            return new Response("Produit mais a jour/mis à jour dans la commande", Response::HTTP_OK);
        }
    
        return new Response("Utilisateur non authentifié", Response::HTTP_FORBIDDEN);
    }


    // Dans votre contrôleur Symfony
#[Route('/api/mobile/user', name: 'api_user')]
public function getUserInfo(): Response
{
    $user = $this->getUser();
    if (!$user) {
        return $this->json(['error' => 'Utilisateur non authentifié'], Response::HTTP_FORBIDDEN);
    }

    return new Response("ok", Response::HTTP_OK);
}
    
#[Route('/api/mobile/commandenonvalidee', name: 'api_commandenonvalidee')]

    public function getCommandesNonValidees(Request $request, CommandesRepository $commandesRepository): Response
    {
        $postdata = json_decode($request->getContent());

        // Vous pouvez récupérer l'utilisateur par son ID ou un autre attribut
        $user = $this->getUser();
      
        if (!$user) {
           
            // Gérer le cas où l'utilisateur n'est pas trouvé
            $response = new Utils;
            return $response->GetJsonResponse($request, ['error' => 'User not found']);
            
        }
        
        $commandesDetails = $commandesRepository->getNonValidatedCommandesDetails($user);

        $response = new Utils;
        return $response->GetJsonResponse($request, $commandesDetails);
    }

    #[Route('/api/mobile/semaine-courante', name: 'api_horaires_semaine_courante')]
    public function getHorairesSemaineCourante(Request $request, PlanningRepository $planningRepository): Response
    {
        $horaires = $planningRepository->trouverHorairesSemaineCourante();

        $utils = new Utils();
        $tab = ["lesReservations"];

        return $utils->GetJsonResponse($request, $horaires,$tab);
    }
    #[Route('/api/mobile/reserver', name: 'api_mobile_reserver')]
    public function reserver(Request $request, EntityManagerInterface $entityManager, CommandesRepository $commandesRepository): Response
    {
        $data = json_decode($request->getContent(), true);
  
        $user = $this->getUser(); // Assurez-vous que votre système d'authentification est correctement configuré
        $jour = new \DateTime($data['jour']); // Utilise DateTime au lieu de DateTimeInterface
    $heure = new \DateTime($data['heureDebut']); // Utilise DateTime au lieu de DateTimeInterface
    $commandeid = $data['commandeId'];
    
        // Recherche de la commande par nomProduit
        $commande = $commandesRepository->find($commandeid);
    
        if (!$commande) {
            return $this->json(['message' => 'Commande introuvable.'], Response::HTTP_NOT_FOUND);
        }
    
        $reservation = new Réserver();
        $reservation->setDate($jour);
        $reservation->setHeure($heure);
        $reservation->setLeUser($user);
        $reservation->setLaCommande($commande); // Association de la commande trouvée à la réservation
        $commande->setValider(true);
        $entityManager->persist($commande);
        $entityManager->persist($reservation);
        $entityManager->flush();
    
        return $this->json(['message' => 'Réservation créée avec succès.'], Response::HTTP_CREATED);
    }
    #[Route('/api/mobile/GetListeProduitRecherche', name: 'api_mobile_recherche')]
    public function GetListeProduitRecherche(Request $request, EntityManagerInterface $entityManager, ProduitsRepository $ProduitsRepository): Response
    {
        $postdata = json_decode($request->getContent());

        $var = $ProduitsRepository->getProduitInfoByMotCle($postdata->motcle); 

        $response = new Utils;
        return $response->GetJsonResponse($request, $var);
    }
    
    #[Route('/api/mobile/Ajoutfavori', name: 'api_Ajoutfavori')]
public function Ajoutfavori(Request $request, EntityManagerInterface $entityManager, FavorisRepository $FavorisRepository, ProduitsRepository $ProduitsRepository): Response
{
    $postdata = json_decode($request->getContent());
    $user = $this->getUser();
    $leProduit = $ProduitsRepository->find($postdata->id);

    if (!$leProduit) {
        return $this->json(['message' => 'Produit introuvable.'], Response::HTTP_NOT_FOUND);
    }

    $favoris = $FavorisRepository->findOneBy(['leUser' => $user, 'leProduit' => $leProduit]);

    // Si aucun favoris n'existe déjà, créer un nouveau favori
    if (!$favoris) {
        $favoris = new Favoris();
        $favoris->setLeUser($user);
        $favoris->setLeProduit($leProduit);
        
        $entityManager->persist($favoris);
        $entityManager->flush();

        return $this->json(['message' => 'Favori ajouté avec succès.'], Response::HTTP_CREATED);
    }

    // Si un favori existe déjà, retourner un message sans créer de doublon
    return $this->json(['message' => 'Le favori existe déjà.'], Response::HTTP_OK);
}
}
