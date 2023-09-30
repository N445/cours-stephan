<?php

namespace App\Service\Module;

use App\Entity\Module\Schedule;
use App\Model\Module\MainModule;
use App\Model\Module\MainModuleEvent;

class ModuleFullCalendarEventsProvider
{
    private Schedule $schedule;

    private ?array $occurencesIDs = [];

    public function __construct(
        private readonly ModuleEventsProvider $moduleEventsProvider
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function getFullcalendarEventsDates(Schedule $schedule, array $modules, ?array $occurencesIDs = []): array
    {
        $this->schedule = $schedule;

        $this->occurencesIDs = $occurencesIDs;


        $dates = [];
        foreach ($modules as $module) {
            $dates = array_merge(
                $dates,
                array_map(
                    fn(MainModuleEvent $mainModuleEvent) => $this->moduleEventToFullCalendarEvent($mainModuleEvent),
                    $this->moduleEventsProvider->getModuleCalendar($schedule, $module)->getEvents()
                )
            );
        }


        return $dates;
    }

    private function moduleEventToFullCalendarEvent(MainModuleEvent $mainModuleEvent): array
    {
        $classes = [
            $mainModuleEvent->isMainEvent() ? 'main-event' : 'sub-event',
            $mainModuleEvent->getOccurenceId(),
            in_array($mainModuleEvent->getOccurenceId(), $this->occurencesIDs) ? 'event-clicked' : ''
        ];

        return [
            "title" => $mainModuleEvent->getTitle(),
            "start" => $mainModuleEvent->getStart()->format(DATE_ATOM),
            "end" => $mainModuleEvent->getEnd()->format(DATE_ATOM),
//            "backgroundColor" => $moduleEvent->isMainEvent() ? "#7C99C3" : '#F0F6FF',
            "classNames" => implode(' ', $classes),
            "extendedProps" => [
                'moduleId' => $mainModuleEvent->getModuleId(),
                'occurenceId' => $mainModuleEvent->getOccurenceId(),
                'type' => $mainModuleEvent->isMainEvent() ? 'main-event' : 'sub-event',
            ],
        ];
    }
}