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
                'BYDAY'    => $day,
                'FREQ'     => RRule::WEEKLY,
                'INTERVAL' => 1,
                'DTSTART'  => $this->shedule->getStartAt()->format('Y-m-d'),
                'UNTIL'    => $this->shedule->getEndAt()->format('Y-m-d'),
            ],
        );
    }

    private function addModulePlanningDayTimes(array &$dates, string $day, \DateTime $occurrence): void
    {
        foreach ($this->getPlanningDaytimes($this->currentPlanning, $day) as $times) {
            $startAt = (clone $occurrence)->add(new \DateInterval($times));
            $endAt   = (clone $startAt)->add(new \DateInterval('PT1H30M'));

            $dates[] = [
                "title" => $this->module->getName(),
                "start" => $startAt->format(DATE_ATOM),
                "end"   => $endAt->format(DATE_ATOM),
            ];

            $this->addModuleSubEvents($dates, $startAt, $endAt);
        }
    }

    private function addModuleSubEvents(array &$dates, \DateTime $startAt, \DateTime $endAt): void
    {
        foreach (range(1, 3) as $subEvent) {
            $startAt->add(new \DateInterval('P1D'));
            $endAt->add(new \DateInterval('P1D'));

            $dates[] = [
                "title" => sprintf('Sous module %s n°%d', $this->module->getName(), $subEvent),
                "start" => $startAt->format(DATE_ATOM),
                "end"   => $endAt->format(DATE_ATOM),
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