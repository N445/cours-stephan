<?php

namespace App\Service\Cart;

class CartPlaceHelper
{
    public static function getChoices(): array
    {
        return [
            'Panier'   => 'cart',
            'Annulé'   => 'cancelled',
            'En cours' => 'pending',
            'Validé'   => 'complete',
        ];
    }

    public static function getLabel(string $key): string
    {
        return array_flip(self::getChoices())[$key] ?? $key;
    }
}