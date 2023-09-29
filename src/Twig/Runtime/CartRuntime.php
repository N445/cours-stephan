<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class CartRuntime implements RuntimeExtensionInterface
{
    public function price_humanize(int $price): string
    {
        return sprintf(
            '%s €',
            number_format(
                $price / 100,
                2,
                ',',
                '.',
            ),
        );
    }
}
