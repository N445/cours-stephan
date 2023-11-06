<?php

namespace App\Service\Cart;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use App\Entity\Module\SubModule;
use App\Service\Module\ModuleEventsProvider;
use Doctrine\ORM\EntityManagerInterface;

class CartValidator
{
    public static function cartIsValid(Cart $cart): bool
    {
        $isValide = true;
        foreach ($cart->getCartItems() as $cartItem) {
            if(!$cartItem->isValid()){
                $isValide = false;
                break;
            }
        }
        return $isValide;
    }
    public static function cartItemIsValid(CartItem $cartItem): bool
    {
        return $cartItem->getModuleDateTime() >= new \DateTime('NOW');
    }
}