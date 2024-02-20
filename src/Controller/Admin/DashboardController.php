<?php

namespace App\Controller\Admin;
use App\Entity\User;
use App\Entity\Produits;
use App\Entity\Commentaires;
use App\Entity\Promo;
use App\Entity\Planning;
use App\Entity\Images;
use App\Entity\Commandes;
use App\Entity\Actualite;
use App\Entity\Categorie;
use App\Entity\CategorieParent;
use App\Entity\CategoriePromo;
use App\Entity\Messages;
use App\Entity\Partenaires;
use App\Entity\Réserver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
     
        return $this->render('admin/dashboard.html.twig');
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="/images/logo.jpg">Dantec Market - Admin</h2>');
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Actualite', 'fa-solid fa-cubes-stacked', Actualite::class);
        yield MenuItem::linkToCrud('Categories', 'fa-solid fa-cubes-stacked', Categorie::class);
        yield MenuItem::linkToCrud('Super Categories', 'fa-solid fa-cubes-stacked', CategorieParent::class);
        yield MenuItem::linkToCrud('Categories Evenements', 'fa-solid fa-cubes-stacked', CategoriePromo::class);
        yield MenuItem::linkToCrud('Commandes', 'fa-solid fa-cubes-stacked', Commandes::class);
        yield MenuItem::linkToCrud('Images', 'fa-solid fa-cubes-stacked', Images::class);
        yield MenuItem::linkToCrud('Messages', 'fa-solid fa-cubes-stacked', Messages::class);
        yield MenuItem::linkToCrud('Partenaires', 'fa-solid fa-cubes-stacked', Partenaires::class);
        yield MenuItem::linkToCrud('Reservations', 'fa-solid fa-cubes-stacked', Réserver::class);
        yield MenuItem::linkToCrud('Produits', 'fa-solid fa-cubes-stacked', Produits::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-users', User::class);
        yield MenuItem::linkToCrud('Commentaires', 'fa-regular fa-comments', Commentaires::class);
        yield MenuItem::linkToCrud('Promo', 'fa-solid fa-tags', Promo::class);
        yield MenuItem::linkToCrud('Planning', 'fa-regular fa-calendar-days', Planning::class);
        yield MenuItem::linkToCrud('Commandes', 'fa-solid fa-basket-shopping', Commandes::class);
    }
}

