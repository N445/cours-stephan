<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module\SubModule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SubModuleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubModule::class;
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
