<?php

namespace App\Controller\Admin;

use App\Entity\Commentaires;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class CommentairesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commentaires::class;
    }

        public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // Cache l'ID lors de la création/édition
            TextEditorField::new('commentaire'),
            NumberField::new('note'),
            DateTimeField::new('dateCommentaire')->setFormat('dd/MM/Y HH:mm:ss'),
            AssociationField::new('leUser') // Ajoute le champ pour sélectionner un utilisateur
                ->setCrudController(UserCrudController::class), // Assurez-vous d'avoir un UserCrudController si vous souhaitez personnaliser l'affichage ou la gestion des utilisateurs dans EasyAdmin
            AssociationField::new('leProduit') // Ajoute le champ pour sélectionner un produit
                ->setCrudController(ProduitsCrudController::class) // Assurez-vous d'avoir un ProduitsCrudController pour la gestion personnalisée des produits
        ];
    }
    
}
