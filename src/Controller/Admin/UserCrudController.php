<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            BooleanField::new('isVerified', 'email vérifiée'),
            AssociationField::new('cart', 'Panier')->hideOnForm(),
            ChoiceField::new('roles', 'Rôles')->setChoices([
                User::ROLE_USER => User::ROLE_USER,
                User::ROLE_ADMIN => User::ROLE_ADMIN,
            ])->allowMultipleChoices(),
        ];
    }
}
