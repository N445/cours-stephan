<?php

namespace App\Twig\Components;

use App\Entity\Cart\Cart;
use App\Entity\Cart\CartItem;
use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use App\Repository\Cart\CartItemRepository;
use App\Repository\Module\ModuleRepository;
use App\Service\Cart\CartHelper;
use App\Service\Cart\CartProvider;
use App\Service\Module\ModuleFullCalendarEventsProvider;
use App\Service\Module\ModuleRRuleProvider;
use App\Service\Module\Schedule\ScheduleFullCalendarEventsProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class ScheduleCalendar
{
    use DefaultActionTrait;
    use ComponentToolsTrait;

    public function __construct(
        private readonly ScheduleFullCalendarEventsProvider $scheduleFullCalendarEventsProvider,
    )
    {
    }

    public array $schedules             = [];

    public function getEvents(): array
    {
        return $this->scheduleFullCalendarEventsProvider->getFullcalendarEventsDates($this->schedules);
    }
}
