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
        return $this->requestStack->getMainRequest()->cookies->get(User::COOKIE_ANONYMOUS__TOKEN);
    }

    public function hasToken(): bool
    {
        return $this->getToken() !== null;
    }

    public function createToken(?Request $request = null): string
    {
        if ($this->hasToken()) {
            return $this->getToken();
        }
        $cookie = $this->getTokenCookie();
        $this->requestStack->getMainRequest()->headers->setCookie($cookie);
        return $cookie->getValue();
    }

    public function getTokenCookie(): Cookie
    {
        return new Cookie(
            User::COOKIE_ANONYMOUS__TOKEN,
            uniqid('anonymous_token_', true),
            time() + 3600 * 24 * 365);
    }
}
