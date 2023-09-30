<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\Cart\CartProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CartProvider           $cartProvider,
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();
        $cart = $this->cartProvider->getUserCart();
        if($user->getCart()){
            $this->em->remove($cart);
            $this->em->flush();
            return;
        }
        if ($cart) {
            $cart->setUser($event->getAuthenticationToken()->getUser())
                ->setAnonymousToken(null);
            $this->em->persist($cart);
            $this->em->flush();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onSecurityAuthenticationSuccess',
        ];
    }
}
