<?php

namespace App\Controller\Admin;

use App\Entity\Reserver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ReserverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reserver::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateTimeField::new('date'),
            DateTimeField::new('heure'),
            AssociationField::new('leUser')
                ->setCrudController(UserCrudController::class),
            AssociationField::new('lePlanning')
                ->setCrudController(PlanningCrudController::class),
            AssociationField::new('laCommande')
                ->setCrudController(CommandesCrudController::class),
        ];
    }
    
}
