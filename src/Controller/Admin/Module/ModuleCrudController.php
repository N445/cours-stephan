<?php

namespace App\Controller\Admin\Module;

use App\Entity\Module\Module;
use App\Entity\Module\Planning;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
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
            NumberField::new('number', 'Numéro du module'),
            ColorField::new('color', 'Couleur'),
            TextField::new('name', 'Nom'),
            AssociationField::new('subModules', 'Sous modules'),
            MoneyField::new('price', 'Prix TTC')->setNumDecimals(2)->setStoredAsCents(true)->setCurrency('EUR'),
            IntegerField::new('nbPlaceBySchedule', 'Place par créneaux'),
            CollectionField::new('plannings', 'Plannings')->useEntryCrudForm(),
        ];
    }
}
