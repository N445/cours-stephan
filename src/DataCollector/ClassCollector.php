<?php

namespace App\DataCollector;

use App\Entity\Cart\Cart;
use App\Service\Cart\CartProvider;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClassCollector extends AbstractDataCollector
{
    public function __construct(
        private readonly Security $security,
        private readonly CartProvider $cartProvider
    )
    {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data = [
            'cart' => $this->cartProvider->getUserCart(),
            'method' => $request->getMethod(),
            'acceptable_content_types' => $request->getAcceptableContentTypes(),
        ];
    }

    public static function getTemplate(): ?string
    {
        return 'data_collector/template.html.twig';
    }

    public function getCart(): ?Cart
    {
        return $this->data['cart'];
    }
}