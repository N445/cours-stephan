<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module\Schedule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ScheduleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Schedule::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            DateField::new('startAt', 'Début'),
            DateField::new('endAt', 'Fin'),
        ];
    }
}
