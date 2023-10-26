<?php

namespace App\Twig\Extension;

use App\Service\Cart\CartPriceHelper;
use App\Service\Cart\CartProvider;
use App\Twig\Runtime\CartRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    public function __construct(
        private readonly CartProvider $cartProvider,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFunction('get_user_cart', [$this->cartProvider, 'getUserCart']),
        ];
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('price_humanize', [CartRuntime::class, 'price_humanize']),
        ];
    }
}
