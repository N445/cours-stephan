<?php

namespace App\Service\Cart;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use App\Service\Module\ModuleEventsProvider;
use Doctrine\ORM\EntityManagerInterface;

class CartHelper
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ModuleEventsProvider   $moduleEventsProvider
    )
    {
    }

    public function hasModuleInCartByOccurenceId(Cart $cart, string $occurenceId): bool
    {
        return (bool)self::getCartItemByOccurenceId($cart, $occurenceId);
    }

    public function addModuleToCartOrRemoveIfExist(Cart $cart, Schedule $schedule, Module $module, string $occurenceId): void
    {
        if ($cartItem = $this->getCartItemByOccurenceId($cart, $occurenceId)) {
            $this->removeCartItemFromCart($cart, $cartItem);
            return;
        }

        $this->addModuleToCart($cart, $schedule, $module, $occurenceId);
    }

    /**
     * @throws \Exception
     */
    public function addModuleToCart(Cart $cart, Schedule $schedule, Module $module, string $occurenceId): void
    {
        $moduleCalendar = $this->moduleEventsProvider->init($schedule)->getModuleCalendar($module);

        $mainModule = $moduleCalendar->getMainModuleByOccurenceId($occurenceId);

        if(!$mainModule){
            return;
        }

        $cart->addCartItem(
            (new CartItem())
                ->setSchedule($schedule)
                ->setScheduleId($schedule->getId())
                ->setScheduleName($schedule->getName())
                ->setModule($module)
                ->setModuleId($module->getId())
                ->setModuleName($module->getName())
                ->setPrice($module->getPrice())
                ->setOccurenceId($occurenceId)
                ->setQuantity(1)
                ->setModuleDateTime($mainModule->getStart())
                ->setMainModule($mainModule)
        );
        $this->em->persist($cart);
        $this->em->flush();
    }

    public function getCartItemByOccurenceId(Cart $cart, string $occurenceId): ?CartItem
    {
        return array_values(
            array_filter($cart->getCartItems()->toArray(),
                function (CartItem $cartItem) use ($occurenceId) {
                    return $cartItem->getOccurenceId() === $occurenceId;
                }
            )
        )[0] ?? null;
    }

    public function removeCartItemFromCart(Cart $cart, CartItem $cartItem): void
    {
        $cart->removeCartItem($cartItem);
        $this->em->remove($cartItem);
        $this->em->flush();
    }
}