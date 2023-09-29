<?php

namespace App\Service\Cart;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AnonymousTokenHelper
{
    public function __construct(
        private readonly RequestStack $requestStack,
    )
    {
    }

    public function getToken(): ?string
    {
        return  $this->requestStack->getSession()->get(User::COOKIE_ANONYMOUS__TOKEN);
    }

    public function hasToken(): bool
    {
        return  $this->requestStack->getSession()->has(User::COOKIE_ANONYMOUS__TOKEN);
    }

    public function createToken(?Request $request = null): string
    {
        if ($this->hasToken()) {
            return $this->getToken();
        }
        $token = uniqid('anonymous_token_', true);
        $this->requestStack->getSession()->set(User::COOKIE_ANONYMOUS__TOKEN, $token);
        return $token;
    }
}
