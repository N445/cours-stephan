<?php

namespace App\Controller;

use App\Service\Cart\CartProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(
        private readonly CartProvider $cartProvider
    )
    {
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartProvider->getUserCart();
        dump($cart);
        return $this->render('cart/index.html.twig', [
        ]);
    }
}
