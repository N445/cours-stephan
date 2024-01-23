<?php

namespace App\Twig\Components;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use App\Model\Module\MainModule;
use App\Model\Module\ModuleCalendar;
use App\Model\Module\ModulesCalendars;
use App\Repository\Cart\CartItemRepository;
use App\Repository\Module\ModuleRepository;
use App\Service\Cart\CartHelper;
use App\Service\Cart\CartProvider;
use App\Service\Module\ModuleEventsProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class CheckoutCalendar
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    #[LiveProp]
    public ?Schedule $schedule = null;

    /**
     * @var Module[]
     */
    public array $modules;

    /**
     * @var array
     */
    public array $modulesFormated;

    #[LiveProp]
    public ?Cart $cart = null;

    public function __construct(
        private readonly ModuleRepository       $moduleRepository,
        private readonly CartProvider           $cartProvider,
        private readonly EntityManagerInterface $em,
        private readonly CartItemRepository     $cartItemRepository,
        private readonly CartHelper             $cartHelper,
        private readonly ModuleEventsProvider   $moduleEventsProvider,
    )
    {
    }

    public function occurencesAddedToCart(): array
    {
        return array_map(static function (CartItem $cartItem) {
            return $cartItem->getOccurenceId();
        }, $this->cart->getCartItems()->toArray());
    }

    /**
     * @throws \Exception
     */
    #[LiveAction]
    public function addModule(#[LiveArg] int $moduleId, #[LiveArg] string $occurenceId): void
    {
        if (!$module = $this->moduleRepository->find($moduleId)) {
            return;
        }

        $this->cart = $this->cartProvider->getUserCartOrCreate();
        $this->cartHelper->addModuleToCartOrRemoveIfExist($this->cart, $this->schedule, $module, $occurenceId);
        $this->cart->sortCartItems();
        $this->refreshEvents();
    }

    /**
     * @throws \Exception
     */
    #[LiveAction]
    public function removeCartItem(#[LiveArg] CartItem $cartItemId): void
    {
        if (!$cartItem = $this->cartItemRepository->find($cartItemId)) {
            return;
        }
        $this->cart = $this->cartProvider->getUserCartOrCreate();
        $this->cartHelper->removeCartItemFromCart($this->cart, $cartItem);
        $this->cart->sortCartItems();
        $this->refreshEvents();
    }

    /**
     * @throws \Exception
     */
    private function refreshEvents(): void
    {
        $this->modules         = $this->moduleRepository->getModulesBySchedule($this->schedule);
        $this->modulesFormated = $this->moduleEventsProvider
            ->init($this->schedule)
            ->getModulesCalendar($this->modules)
        ;
    }
}
