<?php

namespace App\Service\Module;

use App\Entity\Module\Module;
use App\Entity\Module\Planning;
use App\Entity\Module\Shedule;
use RRule\RRule;

class ModuleRRuleProvider
{
    private Shedule   $shedule;
    private ?Planning $currentPlanning;
    private Module    $module;

    public function getModulesRRulesDates(Shedule $shedule, array $modules): array
    {
        $dates = [];
        foreach ($modules as $module) {
            $dates = array_merge($dates, $this->getModuleRRulesDates($shedule, $module));
        }

        return $dates;
    }

    /**
     * @throws \Exception
     */
    public function getModuleRRulesDates(Shedule $shedule, Module $module): array
    {
        $this->shedule         = $shedule;
        if(!$this->currentPlanning = $module->getPlanningByShedule($shedule)){
            return [];
        }
        $this->module          = $module;

        $days = ['MO', 'WE', 'FR'];

        $dates = [];
        foreach ($days as $day) {
            $this->addRRuleOccurencesEvents(
                $dates,
                $day,
            );
        }

        return $dates;
    }

    private function addRRuleOccurencesEvents(array &$dates, string $day): void
    {
        /** @var \DateTime $occurrence */
        foreach ($this->getDayRRule($day) as $occurrence) {
            $this->addModulePlanningDayTimes($dates, $day, $occurrence);
        }
    }

    private function getDayRRule(string $day): RRule
    {
        return new RRule(
            [
                'FREQ'     => RRule::WEEKLY,
                'BYDAY'    => $day,
                'INTERVAL' => 1,
//                'BYSETPOS' => -1,
                'DTSTART'  => $this->shedule->getStartAt()->format('Y-m-d'),
                'UNTIL'    => $this->shedule->getEndAt()->format('Y-m-d'),
            ],
        );
    }

    private function addModulePlanningDayTimes(array &$dates, string $day, \DateTime $occurrence): void
    {
        foreach ($this->getPlanningDaytimes($this->currentPlanning, $day) as $time) {
            $eventId = md5($occurrence->format(DATE_ATOM) . $day . $time);

            $startAt = (clone $occurrence)->add(new \DateInterval($time));
            $endAt   = (clone $startAt)->add(new \DateInterval('PT1H30M'));

            $dates[] = [
                "title"           => $this->module->getName(),
                "start"           => $startAt->format(DATE_ATOM),
                "end"             => $endAt->format(DATE_ATOM),
                "backgroundColor" => "#7c99c3",
                "classNames"      => $eventId . ' main-event',
                "extendedProps"   => [
                    'moduleId'    => $this->module->getId(),
                    'occurenceId' => $eventId,
                    'type'        => 'main-event',
                ],
            ];

            $this->addModuleSubEvents($dates, $startAt, $endAt, $eventId);
        }
    }

    private function addModuleSubEvents(array &$dates, \DateTime $startAt, \DateTime $endAt, string $eventId): void
    {
        $nbSubEvents = 0;

        while ($nbSubEvents !== 3) {
            $startAt->add(new \DateInterval('P1D'));
            $endAt->add(new \DateInterval('P1D'));

            if ($startAt->format('w') === '0') {
                continue;
            }

            $nbSubEvents++;

            $dates[] = [
                "title"           => sprintf('Sous module %s n°%d', $this->module->getName(), $nbSubEvents),
                "start"           => $startAt->format(DATE_ATOM),
                "end"             => $endAt->format(DATE_ATOM),
                "backgroundColor" => "#F0F6FF",
                "textColor"       => "black",
                "classNames"      => $eventId . ' sub-event',
                "extendedProps"   => [
                    'moduleId'    => $this->module->getId(),
                    'occurenceId' => $eventId,
                    'type'        => 'sub-event',
                ],
            ];
        }
    }


    private function getPlanningDaytimes(Planning $planning, string $day): array
    {
        return match ($day) {
            'MO'    => $planning->getMondayTimes(),
            'WE'    => $planning->getWenesdayTimes(),
            'FR'    => $planning->getFridayTimes(),
            default => [],
        };
    }
}