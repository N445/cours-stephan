<?php

namespace App\Service\Cart;

class CartItemLocationHelper
{
    public const PROFESSOR = 'professor';
    public const HOTE      = 'hote';
    public const VISIO     = 'visio';

    public static function getLabel(string $key): string
    {
        return self::getLocations()[$key] ?? $key;
    }

    public static function getLocations(): array
    {
        return [
            self::PROFESSOR => '22 Boulevard de Belleville, 75020 Paris',
            self::HOTE      => 'Domicile d’un hôte de cours',
            self::VISIO     => 'En Visio',
        ];
    }
}