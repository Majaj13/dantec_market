<?php

namespace App\Controller\Admin;

use App\Entity\Produits;
use App\Entity\Images;
use App\Form\ImagesType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class ProduitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produits::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nomProduit', 'Nom du produit'),
            TextEditorField::new('description', 'Description complète'),
            TextField::new('descriptioncourte', 'Description courte'),
            NumberField::new('prix', 'Prix'),
            IntegerField::new('quantiteDisponible', 'Quantité disponible'),
            AssociationField::new('laCategorie', 'Catégorie')
                ->setFormTypeOptions(['by_reference' => true])
                ->autocomplete(),
                CollectionField::new('lesimages', 'Images')
                ->setEntryType(ImagesType::class) // Assurez-vous que ImagesType gère des champs de type URL
                ->setFormTypeOption('by_reference', false) // Important pour la gestion correcte des collections par Doctrine
                ->setFormTypeOption('entry_options', ['label' => false]) // Personnalisez les options selon les besoins
                ->allowAdd(true) // Permettre l'ajout de nouveaux liens d'images
                ->allowDelete(true), // Permettre la suppression de liens d'images
            DateTimeField::new('dateCreation', 'Date de création')->hideOnForm(),
            // Ajoutez d'autres champs selon vos besoins
        ];
    }
}
