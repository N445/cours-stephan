<?php

namespace App\Controller\Admin;

use App\Entity\Testimonial\Testimonial;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TestimonialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Testimonial::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            EmailField::new('email', 'Email'),
            TextEditorField::new('message', 'Message'),
            BooleanField::new('enable', 'Activé'),
            DateTimeField::new('createdAt','Créer le'),
        ];
    }
}
