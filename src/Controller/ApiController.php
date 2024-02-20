<?php
namespace App\Controller;
use App\Repository\UserRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;

use App\Repository\CategorieParentRepository;
use App\Repository\CommandesRepository;
use App\Repository\PlanningRepository;
use App\Repository\FavorisRepository;
use App\Repository\PartenairesRepository;
use App\Repository\ActualiteRepository;
use App\Repository\CommentairesRepository;
use App\Repository\CommanderRepository;
use App\Repository\MessagesRepository;
use App\Entity\User;
use App\Entity\Réserver;
use App\Entity\Commandes;
use App\Entity\Commander;
use App\Entity\Categorie;
use App\Entity\Favoris;
use App\Entity\Commentaires;
use App\Entity\Messages;
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
$tab = ["lesReservations","lesCommandes","lesCommentaires","lesFavoris"];
return $response->GetJsonResponse($request, $user,$tab);
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
$tab = ["laCategorie","lesFavoris","lesCommandes","leProduit","lesProduits","lacategorieParent","leUser","lesPromos"];
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
#[Route('/api/mobile/produitdujour', name: 'app_api_mobile_GetPromoduJour')]
public function GetPromoduJour(Request $request, ProduitsRepository $produitsRepository)
{
$postdata = json_decode($request->getContent());
$var =  $produitsRepository->getProduitPromoDuJour($postdata->jour);
$response = new Utils;
return $response->GetJsonResponse($request, $var);
}
#[Route('/api/mobile/produitType', name: 'app_api_mobile_GetproduitType')]
public function GetproduitType(Request $request, ProduitsRepository $produitsRepository)
{
$postdata = json_decode($request->getContent());
$var =  $produitsRepository->getProduitPromoDuJour($postdata->promo);
$response = new Utils;
return $response->GetJsonResponse($request, $var);
}
#[Route('/api/mobile/produitmoinsvendu', name: 'app_api_mobile_Getproduitmoinsvendu')]
public function Getproduitmoinsvendu(Request $request, ProduitsRepository $produitsRepository)
{
$var =  $produitsRepository->findLeastSoldLastWeek();
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
$commander->setNoteDonnee(0);
$manager->persist($commander);
$commande->setMontantTotal($MontantTotalActuel + $postdata->prix);
$commande->setValider(false);
$commande->setEtat('-');
$manager->persist($commande);
} else {
// Produit pas encore dans la commande, l'ajouter
$commander = new Commander();
$MontantTotalActuel = $commande->getMontantTotal();
$commander->setLeProduit($produit);
$commander->setQuantite($postdata->quantite);
$commander->setPrixretenu(floatval($postdata->prix));
$commander->setLaCommande($commande);
$commander->setNoteDonnee(0);
$manager->persist($commander);
$commande->setMontantTotal($MontantTotalActuel + $postdata->prix);
$commande->setValider(false);
$commande->setEtat('-');
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
$commande->setEtat('A confirmer');
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
#[Route('/api/mobile/modifieruser', name: 'api_modifieruser')]
public function modifieruser(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
{
$postdata = json_decode($request->getContent());
$user = $this->getUser();
$user = $userRepository->find($user->getId());
if (!$user) {
return $this->json(['message' => 'Produit introuvable.'], Response::HTTP_NOT_FOUND);
}
// Mettez à jour les propriétés de l'utilisateur
$user->setNom($postdata->nom);
$user->setPrenom($postdata->prenom);
$user->setEmail($postdata->email);
$user->setTelephone($postdata->telephone);
$user->setRoles(['ROLE_ADMIN']);
$user->setClasse($postdata->classe);
// Persistez les changements
$entityManager->persist($user);
$entityManager->flush();
// Retournez une réponse ou redirigez l'utilisateur
return new Response('User updated successfully');
}
#[Route('/api/mobile/compteuser', name: 'app_api_compteuser')]
public function getcompteuser(Request $request, UserRepository $userRepository)
{
$postdata = json_decode($request->getContent());
$user = $this->getUser();
$var = $userRepository->find($user->getId());
if (!$user) {
return $this->json(['message' => 'user introuvable.'], Response::HTTP_NOT_FOUND);
}
$response = new Utils;
$tab = ["lesFavoris","lesCommandes","lesReservations","lesCommentaires","password"];
return $response->GetJsonResponse($request, $var,$tab);
}
#[Route('/api/mobile/GetLesCommandes', name: 'app_api_GetLesCommandes')]
public function GetLesCommandes(Request $request, CommandesRepository $commandesRepository)
{
$postdata = json_decode($request->getContent());
$user = $this->getUser();
$var = $commandesRepository->findValidatedOrdersByUser($user);
$response = new Utils;
$tab = ["lesCommandes","lesReservations","laCategorie","lesCommentaires","leUser"];
return $response->GetJsonResponse($request, $var,$tab);
}
#[Route('/api/mobile/detailcommande', name: 'api_detailcommande')]
public function getDetailCommandeId(Request $request, CommandesRepository $commandesRepository): Response
{
$postdata = json_decode($request->getContent());
// Vous pouvez récupérer l'utilisateur par son ID ou un autre attribut
$user = $this->getUser();
if (!$user) {
// Gérer le cas où l'utilisateur n'est pas trouvé
$response = new Utils;
return $response->GetJsonResponse($request, ['error' => 'User not found']);
}
$commandesDetails = $commandesRepository->getValidatedCommandesDetails( $postdata->Id);
$response = new Utils;
return $response->GetJsonResponse($request, $commandesDetails);
}
#[Route('/api/mobile/getListe', name: 'api_mobile_getListe')]
public function getListe(Request $request, EntityManagerInterface $entityManager,UserRepository $userRepository ,FavorisRepository $favorisRepository): Response
{
$postdata = json_decode($request->getContent());
$user = $this->getUser();
$user = $userRepository->find($user->getId());
$var = $favorisRepository->getListeFavoris($user); 
$response = new Utils;
return $response->GetJsonResponse($request, $var);
}
#[Route('/api/mobile/supprimerfavoris', name: 'api_mobile_supprimerfavoris', methods: ['POST'])]
public function supprimerFavoris(Request $request, EntityManagerInterface $entityManager,ProduitsRepository $produitRepository,UserRepository $userRepository, FavorisRepository $favorisRepository): Response
{
$postdata = json_decode($request->getContent(), true);
if (!isset($postdata['id'])) {
return $this->json(['message' => 'ID du favori non fourni'], Response::HTTP_BAD_REQUEST);
}
$user = $this->getUser();
$user = $userRepository->find($user->getId());
$produit = $produitRepository->find($postdata['id']);
$favori = $favorisRepository->findOneBy(['leProduit' => $postdata['id'], 'leUser' => $user]);
if (!$favori) {
return $this->json(['message' => 'Favori non trouvé'], Response::HTTP_NOT_FOUND);
}
$entityManager->remove($favori);
$entityManager->flush();
$response = new Utils; // Utilisez votre méthode de réponse JSON
return $response->GetJsonResponse($request, ['message' => 'Favori supprimé avec succès']);
}
#[Route('/api/mobile/getPartenaires', name: 'api_mobile_getPartenaires')]
public function getPartenaires(Request $request, EntityManagerInterface $entityManager,PartenairesRepository $partenairesRepository): Response
{
$postdata = json_decode($request->getContent());
$var = $partenairesRepository->findAll(); 
$response = new Utils;
return $response->GetJsonResponse($request, $var);
}
#[Route('/api/mobile/getLesActualites', name: 'api_mobile_getLesActualites')]
public function getLesActualites(Request $request, EntityManagerInterface $entityManager,ActualiteRepository $actualiteRepository): Response
{
$postdata = json_decode($request->getContent());
$var = $actualiteRepository->findAll(); 
$response = new Utils;
return $response->GetJsonResponse($request, $var);
}
#[Route('/api/mobile/versverifierfavoris', name: 'api_mobile_versverifierfavoris')]
public function getversverifierfavoris(Request $request, EntityManagerInterface $entityManager,ProduitsRepository $produitRepository,UserRepository $userRepository,FavorisRepository $FavorisRepository): Response
{
$postdata = json_decode($request->getContent());
$user = $this->getUser();
$user = $userRepository->find(1);
$produit = $produitRepository->find($postdata->id);
$var = $FavorisRepository->findOneBy(['leProduit' => $produit,'leUser' => $user]); 
$estFavori = $var !== null; // true si trouvé, false sinon
// Renvoie une réponse JSON avec true ou false
return $this->json(['estFavori' => $estFavori]);
}

#[Route('/api/mobile/GetLesetEtoiles', name: 'api_GetLesetEtoiles')]
public function GetLesetEtoiles(Request $request,CommandesRepository $commandesRepository,CommanderRepository $commanderRepository, ProduitsRepository $produitRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
{
$postdata = json_decode($request->getContent());
$user = $this->getUser();
$user = $userRepository->find($user->getId());
$produit = $produitRepository->find($postdata->idProduit);
$commande = $commandesRepository->find($postdata->idCommande);
$commander = $commanderRepository->findOneBy(['laCommande'=>$commande,'leProduit'=>$produit]);

if (!$user) {
return $this->json(['message' => 'user introuvable.'], Response::HTTP_NOT_FOUND);
}
if (!$produit) {
return $this->json(['message' => 'produit introuvable.'], Response::HTTP_NOT_FOUND);
}
if (!$commander) {
    return $this->json(['message' => 'produit introuvable.'], Response::HTTP_NOT_FOUND);
    }

// Mettez à jour les propriétés de l'utilisateur
$produit->setNbvotes($produit->getNbvotes()+1);
$produit->setNbAvis($produit->getNbAvis()+$postdata->nombreEtoiles);
$produit->setEtoiles($produit->getNbAvis()/$produit->getNbvotes());

$commander->setNoteDonnee(1);

// Persistez les changements
$entityManager->persist($produit);
$entityManager->persist($commande);
$entityManager->flush();
// Retournez une réponse ou redirigez l'utilisateur
return new Response('commentaire updated successfully');
}


#[Route('/api/mobile/creermessage', name: 'api_Getcreermessage')]
public function getCreermessage(Request $request, MessagesRepository $messageRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
{
    $postdata = json_decode($request->getContent());
    $user = $this->getUser();
    $user = $userRepository->find($user->getId());

    $message = new Messages();
    $message->setleUser($user);
    $message->setMessage($postdata->message);
    $message->setDateMessage(new \DateTime());
    $message->setEtat('A traiter');
    $message->setReponse('-');

// Persistez les changements
$entityManager->persist($message);
$entityManager->flush();

    return new Response('commentaire created successfully');

}

#[Route('/api/mobile/touslesmessages', name: 'api_mobile_gettouslesmessages')]
public function gettouslesmessages(Request $request, EntityManagerInterface $entityManager,MessagesRepository $messagesRepository,UserRepository $userRepository): Response
{
    $postdata = json_decode($request->getContent());
    $user = $this->getUser();
    $user = $userRepository->find($user->getId());
$var = $messagesRepository->findBy(['leUser'=>$user]); 
$response = new Utils;
$tab = ["lesCommandes","lesReservations","laCategorie","lesCommentaires","leUser"];
return $response->GetJsonResponse($request, $var,$tab);
}
#[Route('/api/mobile/getLesCategories', name: 'api_mobile_getLesCategories')]
public function getLesCategories(Request $request, EntityManagerInterface $entityManager,CategorieRepository $categorieRepository): Response
{
$postdata = json_decode($request->getContent());
$var = $categorieRepository->findAll(); 
$response = new Utils;
$tab = ["lesProduits","lacategorieParent"];

return $response->GetJsonResponse($request, $var,$tab);
}
}