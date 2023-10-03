<?php

namespace App\Service\Module;

use App\Entity\Module\Schedule;
use App\Model\Module\MainModule;
use App\Model\Module\MainModuleEvent;

class ModuleFullCalendarEventsProvider
{
    private ?array $occurencesIDs = [];

    public function __construct(
        private readonly ModuleEventsProvider $moduleEventsProvider,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function getFullcalendarEventsDates(Schedule $schedule, array $modules, ?array $occurencesIDs = []): array
    {
        $this->occurencesIDs = $occurencesIDs;


        $modulesCalendars = $this->moduleEventsProvider
            ->init($schedule)
            ->getModulesCalendar($modules)
        ;

        $dates = [];
        foreach ($modulesCalendars as $moduleCalendar) {
            $dates = array_merge(
                $dates,
                array_map(
                    fn(MainModuleEvent $mainModuleEvent) => $this->moduleEventToFullCalendarEvent($mainModuleEvent),
                    $moduleCalendar->getEvents(),
                ),
            );
        }


        return $dates;
    }

    private function moduleEventToFullCalendarEvent(MainModuleEvent $mainModuleEvent): array
    {
        $classes = [
            $mainModuleEvent->isMainEvent() ? 'main-event' : 'sub-event',
            $mainModuleEvent->getOccurenceId(),
            in_array($mainModuleEvent->getOccurenceId(), $this->occurencesIDs) ? 'event-clicked' : '',
        ];

        return [
            "title"         => $mainModuleEvent->getTitle(),
            "start"         => $mainModuleEvent->getStart()->format(DATE_ATOM),
            "end"           => $mainModuleEvent->getEnd()->format(DATE_ATOM),
//            "backgroundColor" => $moduleEvent->isMainEvent() ? "#7C99C3" : '#F0F6FF',
            "classNames"    => implode(' ', $classes),
            "extendedProps" => [
                'moduleId'    => $mainModuleEvent->getModuleId(),
                'occurenceId' => $mainModuleEvent->getOccurenceId(),
                'type'        => $mainModuleEvent->isMainEvent() ? 'main-event' : 'sub-event',
            ],
        ];
    }
}