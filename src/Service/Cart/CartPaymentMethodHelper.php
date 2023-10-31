<?php

namespace App\Service\Cart;

class CartPaymentMethodHelper
{
    public static function getChoices(): array
    {
        return [
            'PayPal'     => 'paypal',
            'Hors ligne' => 'offline',
        ];
    }

    public static function getLabel(string $key): string
    {
        return array_flip(self::getChoices())[$key] ?? $key;
    }
}