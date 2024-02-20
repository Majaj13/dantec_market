<?php

namespace App\Controller\Admin;

use App\Entity\CategorieParent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\CategorieType; // Assurez-vous d'importer l'entité Categorie correctement

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\CrudAutocompleteType;


class CategorieParentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategorieParent::class;
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            CollectionField::new('lescategories')
                ->setEntryType(CategorieType::class) // Assurez-vous que CategorieType est le bon type de formulaire pour vos catégories
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->onlyOnForms(), // Optionnel: à utiliser si vous souhaitez afficher ce champ uniquement sur les formulaires
        ];
    }


    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
