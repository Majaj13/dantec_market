<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProduitsRepository;
use App\Repository\CategorieParentRepository;
use App\Entity\User;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}
