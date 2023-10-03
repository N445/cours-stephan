<?php

namespace App\Service\Module;

use App\Entity\Module\Schedule;
use App\Repository\Cart\CartRepository;

class ModuleOccurenceCounter
{
    public function __construct(
        private readonly CartRepository $cartRepository,
    )
    {
    }

    public function getNbOccurenceBySchedule(Schedule $schedule, ?string $occurenceId = null): array
    {
        $alreadyResevedOccurence = [];
        foreach ($this->cartRepository->findBySchedule($schedule, $occurenceId) as $cart) {
            foreach ($cart->getCartItems() as $cartItem) {
                if (!($alreadyResevedOccurence[$cartItem->getOccurenceId()] ?? null)) {
                    $alreadyResevedOccurence[$cartItem->getOccurenceId()] = $cartItem->getQuantity();
                    continue;
                }
                $alreadyResevedOccurence[$cartItem->getOccurenceId()] += $cartItem->getQuantity();
            }
        }

        return $alreadyResevedOccurence;
    }
}