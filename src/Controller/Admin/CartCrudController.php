<?php

namespace App\Controller\Admin;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class CartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cart::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('createdAt', 'Créé le'),
            DateTimeField::new('updatedAt', 'Modifié le'),
            AssociationField::new('user', 'Utilisateur'),
            ChoiceField::new('place', 'État')->setChoices([
                                                              'Panier'   => 'cart',
                                                              'Annulé'   => 'cancelled',
                                                              'En cours' => 'pending',
                                                              'Validé'   => 'complete',
                                                          ]),
            MoneyField::new('total', 'Total')->setCurrency('EUR'),
            AssociationField::new('cartItems', 'Modules')->onlyOnIndex(),
            AssociationField::new('cartItems', 'Modules')->onlyOnDetail()->setTemplatePath('admin/cart/cart-item.html.twig')
            ,
        ];
    }
}
