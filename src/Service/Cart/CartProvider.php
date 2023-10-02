<?php

namespace App\Service\Cart;

use App\Entity\Cart\Cart;
use App\Entity\User;
use App\Repository\Cart\CartRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\InMemoryUser;

class CartProvider
{
    private ?User   $user           = null;
    private ?string $anonymousToken = null;

    public function __construct(
        private readonly CartRepository         $cartRepository,
        private readonly Security               $security,
        private readonly EntityManagerInterface $em,
        private readonly AnonymousTokenHelper   $anonymousTokenHelper,
    )
    {
    }

    private function init(): void
    {
        $this->user = $this->security->getUser();
        if (!$this->user) {
            if (!$this->anonymousToken = $this->anonymousTokenHelper->getToken()) {
                $this->anonymousToken = $this->anonymousTokenHelper->createToken();
            }
        }

    }

    public function getAnonymousCart(): ?Cart
    {
        $this->init();
        if (!$this->anonymousToken) {
            return null;
        }
        return $this->cartRepository->findByAnonymousToken($this->anonymousToken);
    }

    public function getUserCart(): ?Cart
    {
        $this->init();
        if (!$this->user) {
            return $this->cartRepository->findByAnonymousToken($this->anonymousToken);
        }


        return $this->cartRepository->findByUser($this->user);
    }

    public function getUserCartOrCreate(): Cart
    {
        $this->init();
        if (!$cart = $this->getUserCart()) {
            return $this->createCart();
        }

        return $cart;
    }


    private function createCart(): Cart
    {
        if ($this->user) {
            $cart = (new Cart())->setUser($this->user);
        } else {
            $cart = (new Cart())->setAnonymousToken($this->anonymousToken);
        }

        $this->em->persist($cart);
        $this->em->flush();
        return $cart;
    }
}