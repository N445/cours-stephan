<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module\Planning;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class PlanningCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Planning::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $timesChoices =        [
            '9h00 - 10h30' => 'PT9H',
            '11h00 - 12h30' => 'PT11H',
            '13h30 - 15h00' => 'PT13H30M',
            '15h30 - 17h00' => 'PT15H30M',
            '17h30 - 19h00' => 'PT17H30M',
        ];


        return [
            AssociationField::new('shedule'),
            ChoiceField::new('mondayTimes')->allowMultipleChoices()->setChoices($timesChoices),
            ChoiceField::new('wenesdayTimes')->allowMultipleChoices()->setChoices($timesChoices),
            ChoiceField::new('fridayTimes')->allowMultipleChoices()->setChoices($timesChoices),
        ];
    }
}
