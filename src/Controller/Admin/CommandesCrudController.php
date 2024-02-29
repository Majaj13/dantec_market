<?php

namespace App\Controller\Admin;

use App\Entity\Commandes;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;


class CommandesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commandes::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $produitsDetails = ArrayField::new('lesCommandes')
        ->formatValue(function ($value, $entity) {
            $details = [];
            /** @var Commandes $commande */
            $commande = $entity;
            foreach ($commande->getLesCommandes() as $ligneCommande) {
                /** @var Commander $ligneCommande */
                $details[] = sprintf("%s: %d x %s €",
                    $ligneCommande->getLeProduit()->getNomProduit(),
                    $ligneCommande->getQuantite(),
                    number_format($ligneCommande->getPrixretenu(), 2, '.', ''));
            }
            return implode(", ", $details);
        })->hideOnForm();
        return [

            IdField::new('id')->hideOnForm(),
            DateTimeField::new('dateCommande', 'Date de commande'),
            NumberField::new('montantTotal', 'Montant total'),
            AssociationField::new('leUser', 'Utilisateur')
                ->Autocomplete(),
            BooleanField::new('valider', 'Validée ?'),
           ChoiceField::new('etat', 'État')
            ->setChoices([
                'Confirmée' => 'Confirmée',
                'En cours de traitement' => 'En cours de traitement',
                'Traitée' => 'Traitée',
                'Livré' => 'Livrée',
            ]),
            AssociationField::new('lesReservations', 'Réservations')
                ->Autocomplete(),
                TextField::new('planningDetails', 'Détails du Planning')
                ->formatValue(function ($value, $entity) {
                    return $entity->getPlanningDetails();
                })
                ->onlyOnDetail(),
                $produitsDetails, // ou ->onlyOnIndex(), selon où vous souhaitez afficher cette information
            
           
        ];
    }
}
