<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module\Module;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ModuleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Module::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            AssociationField::new('subModules', 'Sous modules'),
            ChoiceField::new('days', 'Jours')->allowMultipleChoices()->setChoices(
                [
                    'Lundi' => 'MO',
                    'Mercredi' => 'WE',
                    'Vendredi' => 'FR',
                ]
            )->renderExpanded(),
            ChoiceField::new('hours', 'Heures')->allowMultipleChoices()->setChoices(
                [
                    '9h00 - 10h30' => 'PT9H',
                    '11h00 - 12h30' => 'PT11H',
                    '13h30 - 15h00' => 'PT13H30M',
                    '15h30 - 17h00' => 'PT15H30M',
                    '17h30 - 19h00' => 'PT17H30M',
                ]
            )->renderExpanded(),
        ];
    }
}
