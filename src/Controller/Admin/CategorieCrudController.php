<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(), // Cache l'ID sur le formulaire pour éviter les modifications
        TextField::new('nom'), // Assurez-vous que le nom correspond à celui de votre entité
        AssociationField::new('lacategorieParent')
            ->setLabel('Catégorie Parent') // Modifiez le label selon vos besoins
            ->setHelp('Sélectionnez la catégorie parent') // Optionnel : ajoute un texte d'aide
            ->autocomplete(), // Active l'autocomplétion pour faciliter la recherche
    ];
}

}
