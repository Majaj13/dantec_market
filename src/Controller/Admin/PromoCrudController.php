<?php
namespace App\Controller\Admin;
use App\Entity\Produit;
use App\Entity\Promo;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PromoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Promo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // Hide on form as it's auto-generated
            DateTimeField::new('dateDebut', 'Date de début'),
            DateTimeField::new('dateFin', 'Date de fin'),
            NumberField::new('prix', 'Prix'),
            AssociationField::new('leProduit', 'Produit')
                ->setCrudController(ProduitsCrudController::class), // Adjust if you have a specific controller for produits
            AssociationField::new('laCategoriePromo', 'Catégorie Promo')
                ->setCrudController(CategoriePromoCrudController::class) // Adjust if you have a specific controller for categorie promos
        ];
    }
}
