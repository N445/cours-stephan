<?php

namespace App\Twig\Components;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Entity\Module\Shedule;
use App\Repository\Module\ModuleRepository;
use App\Service\Cart\CartProvider;
use App\Service\Module\ModuleRRuleProvider;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class CheckoutCalendar
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?Shedule $shedule = null;
    #[LiveProp]
    public array    $events  = [];
    #[LiveProp]
    public ?Cart    $cart    = null;

    public function __construct(
        private readonly ModuleRepository    $moduleRepository,
        private readonly ModuleRRuleProvider $moduleRRuleProvider,
        private readonly CartProvider        $cartProvider,
    )
    {
    }

    #[LiveAction]
    public function selectModule(#[LiveArg] int $moduleId, #[LiveArg] \DateTime $dateTime): void
    {
        if (!$module = $this->moduleRepository->find($moduleId)) {
            return;
        }

        $this->cart = $this->cartProvider->getUserCart();
        $this->cart->addCartItem(
            (new CartItem())
                ->setModule($module)
                ->setModuleName($module->getName())
                ->setPrice($module->getPrice())
                ->setQuantity(1)
                ->setModuleDateTime($dateTime)
        );

        dump($this->cart->getCartItems()->toArray());
    }
}
