<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module\Planning;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PlanningCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Planning::class;
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
