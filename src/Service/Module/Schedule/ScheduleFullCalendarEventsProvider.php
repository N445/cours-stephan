<?php

namespace App\Service\Module\Schedule;

use App\Entity\Module\Schedule;
use App\Service\Helper\ColorHelper;
use Symfony\Component\Routing\RouterInterface;

class ScheduleFullCalendarEventsProvider
{
    public function __construct(
        private readonly RouterInterface $router,
    )
    {
    }

    public function getFullcalendarEventsDates(array $schedules): array
    {
        return array_map(function (Schedule $schedule): array {
            return $this->getFullCalendarEvent($schedule);
        }, $schedules);
    }

    private function getFullCalendarEvent(Schedule $schedule): array
    {
        $classes = [];

        return [
            "title"           => $schedule->getName(),
            "start"           => $schedule->getStartAt()->format(DATE_ATOM),
            "end"             => $schedule->getEndAt()->format(DATE_ATOM),
            "backgroundColor" => $schedule->getColor() ?: null,
            "textColor"       => $schedule->getColor() ? ColorHelper::getContrastColor($schedule->getColor()) : null,
            "borderColor"     => $schedule->getColor() ?: null,
            "classNames"      => implode(' ', $classes),
            'url'             => $this->router->generate('APP_RESERVATION', [
                'scheduleId' => $schedule->getId(),
            ]),
            "extendedProps"   => [],
        ];
    }
}