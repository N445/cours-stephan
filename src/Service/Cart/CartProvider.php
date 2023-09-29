<?php

namespace App\Service\Cart;

use App\Entity\Cart\Cart;
use App\Entity\User;
use App\Repository\Cart\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CartProvider
{
    public function __construct(
        private readonly CartRepository         $cartRepository,
        private readonly Security               $security,
        private readonly EntityManagerInterface $em,
        private readonly AnonymousTokenHelper   $anonymousTokenHelper,
    )
    {
    }

    public function getUserCart(): ?Cart
    {
        /** @var User $user */
        if (!$user = $this->security->getUser()) {
            return $this->getAnonymousCart();
        }

        if (!$cart = $this->cartRepository->findByUSer($user)) {
            return $this->createUserCart($user);
        }

        return $cart;
    }

    private function createUserCart(User $user): Cart
    {
        $cart = (new Cart())->setUser($user);
        $this->em->persist($cart);
        $this->em->flush();
        return $cart;
    }

    private function getAnonymousCart(): ?Cart
    {
        if (!$this->anonymousTokenHelper->hasToken()) {
            $token = $this->anonymousTokenHelper->createToken();
            return $this->createAnonymousCart($token);
        }

        $token = $this->anonymousTokenHelper->getToken();

        if (!$cart = $this->cartRepository->findByAnonymousToken($token)) {
            return $cart;
        }

        return $this->createAnonymousCart($token);
    }

    private function createAnonymousCart(string $token): ?Cart
    {
        $cart = (new Cart())->setAnonymousToken($token);
        $this->em->persist($cart);
        $this->em->flush();
        return $cart;
    }
}