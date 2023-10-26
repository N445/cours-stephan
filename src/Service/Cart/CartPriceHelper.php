<?php

namespace App\Service\Cart;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use App\Entity\Module\SubModule;
use App\Service\Module\ModuleEventsProvider;
use Doctrine\ORM\EntityManagerInterface;

class CartPriceHelper
{
    public static function getCartItemPrice(CartItem $cartItem): int
    {
        $totalPrice = $cartItem->getPrice() * $cartItem->getQuantity();

        $promotion = $cartItem->isLocationHote() && $cartItem->getQuantity() >= 5 ? $cartItem->getPrice() : 0;

        return $totalPrice - $promotion;
    }

    public static function getCartPrice(Cart $cart): int
    {
        return array_reduce(
            $cart->getCartItems()->toArray(),
            static function (int $carry, CartItem $cartItem) {
                $carry += $cartItem->getTotal();
                return $carry;
            },
            0,
        );
    }
}