<?php

namespace App\Controller\Admin;

use App\Entity\Cart\CartItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CartItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CartItem::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('moduleName'),
            IntegerField::new('quantity'),
            TextField::new('location'),
        ];
    }
}
