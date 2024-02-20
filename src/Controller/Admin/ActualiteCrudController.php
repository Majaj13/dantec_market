<?php
namespace App\Controller\Admin;
use App\Entity\Actualite;
use App\Entity\Images;
use App\Form\ImagesType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class ActualiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Actualite::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // ID généré automatiquement, caché dans les formulaires
            TextField::new('titre', 'Titre'),
            TextareaField::new('texte', 'Texte'),
            CollectionField::new('lesimages', 'Images')
                ->setEntryType(ImagesType::class) // Assurez-vous que ImagesType gère des champs de type URL
                ->setFormTypeOption('by_reference', false) // Important pour la gestion correcte des collections par Doctrine
                ->setFormTypeOption('entry_options', ['label' => false]) // Personnalisez les options selon les besoins
                ->allowAdd(true) // Permettre l'ajout de nouveaux liens d'images
                ->allowDelete(true), // Permettre la suppression de liens d'images
        ];
    }
    
}
