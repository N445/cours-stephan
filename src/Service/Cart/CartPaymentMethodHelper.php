<?php

namespace App\Service\Cart;

class CartPaymentMethodHelper
{
    public static function getChoices(): array
    {
        return [
            'Paypal ou carte bancaire'     => 'paypal',
            'Hors ligne' => 'offline',
        ];
    }

    public static function getLabel(string $key): string
    {
        return array_flip(self::getChoices())[$key] ?? $key;
    }
}