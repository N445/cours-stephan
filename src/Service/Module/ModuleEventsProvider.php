<?php

namespace App\Service\Module;

use App\Entity\Module\Module;
use App\Entity\Module\Planning;
use App\Entity\Module\Schedule;
use App\Model\Module\MainModule;
use App\Model\Module\MainModuleEvent;
use App\Model\Module\ModuleCalendar;

class ModuleEventsProvider
{
    private Schedule       $schedule;
    private ?Planning      $currentPlanning = null;
    private Module         $module;
    private ModuleCalendar $moduleCalendar;
    private ?string        $occurenceId;

    private array $alreadyResevedOccurence = [];

    public function __construct(
        private readonly ModuleOccurenceCounter $moduleOccurenceCounter,
    )
    {
    }

    public function init(Schedule $schedule, ?string $occurenceId = null): self
    {
        $this->schedule    = $schedule;
        $this->occurenceId = $occurenceId;
        $this->initAlreadyReservedOccurence();
        return $this;
    }

    /**
     * @return ModuleCalendar[]
     * @throws \Exception
     */
    public function getModulesCalendar(array $modules): array
    {
        $moduleCalendars = [];
        foreach ($modules as $module) {
            $moduleCalendars[] = $this->getModuleCalendar($module);
        }


        return $moduleCalendars;
    }

    public function getModuleCalendar(Module $module): ModuleCalendar
    {
        $this->moduleCalendar = (new ModuleCalendar())
            ->setSchedule($this->schedule)
            ->setModule($module)
        ;

        if (!$this->currentPlanning = $module->getPlanningBySchedule($this->schedule)) {
            return $this->moduleCalendar;
        }

        $this->module = $module;

        $days = ['MO', 'WE', 'FR'];
        foreach ($days as $day) {
            $this->addRRuleOccurencesEvents(
                $day,
            );
        }

        foreach ($this->moduleCalendar->getMainModules() as $mainModule) {
            if ($mainModule->getEnd() > $this->schedule->getEndAt()) {
                $this->moduleCalendar->removeMainModules($mainModule);
            }
        }

        return $this->moduleCalendar;
    }

    private function initAlreadyReservedOccurence(): void
    {
        $this->alreadyResevedOccurence = $this->moduleOccurenceCounter->getNbOccurenceBySchedule($this->schedule);
    }

    private function addRRuleOccurencesEvents(string $day): void
    {
        /** @var \DateTime $occurrence */
        foreach (ModuleRRuleProvider::getDayRRule($day, $this->schedule->getStartAt(), $this->schedule->getEndAt()) as $occurrence) {
            $this->addModulePlanningDayTimes($day, $occurrence);
        }
    }

    private function addModulePlanningDayTimes(string $day, \DateTime $occurrence): void
    {
        foreach ($this->getPlanningDaytimes($this->currentPlanning, $day) as $time) {
            $occurenceId = md5(
                implode('-', [
                    $this->schedule->getId(),
                    $this->module->getId(),
                    $occurrence->format(DATE_ATOM),
                    $day,
                    $time,
                ]),
            );

            if ($this->occurenceId && $this->occurenceId !== $occurenceId) {
                continue;
            }

            if ($nbReservedPlaceWithThisOccurence = $this->alreadyResevedOccurence[$occurenceId] ?? null) {
                if ($nbReservedPlaceWithThisOccurence >= $this->module->getNbPlaceBySchedule()) {
                    continue;
                }
            }

            $startAt = (clone $occurrence)->add(new \DateInterval($time));
            $endAt   = (clone $startAt)->add(new \DateInterval('PT1H30M'));

            $firstEvent = (new MainModuleEvent())
                ->setTitle($this->module->getName())
                ->setStart($startAt)
                ->setEnd($endAt)
                ->setIsMainEvent(true)
                ->setOccurenceId($occurenceId)
                ->setModuleId($this->module->getId())
            ;

            $mainModule = (new MainModule())
                ->setTitle($this->module->getName())
                ->setModuleId($this->module->getId())
                ->setOccurenceId($occurenceId)
                ->setAvailable(true)
                ->setNbPlaces($this->module->getNbPlaceBySchedule())
                ->addMainModuleEvent($firstEvent)
            ;

            $this->addModuleSubEvents($mainModule, $startAt, $endAt);

            $mainModule->setStart($mainModule->getMainModuleEvents()->first()->getStart());
            $mainModule->setEnd($mainModule->getMainModuleEvents()->last()->getEnd());

            $this->moduleCalendar->addMainModules($mainModule);

        }
    }

    private function addModuleSubEvents(MainModule $mainModule, \DateTime $startAt, \DateTime $endAt): void
    {
        $nbSubEvents = 0;

        while ($nbSubEvents !== 3) {
            $startAt = (clone $startAt)->add(new \DateInterval('P1D'));
            $endAt   = (clone $endAt)->add(new \DateInterval('P1D'));

            if ($startAt->format('w') === '0') {
                continue;
            }

            $nbSubEvents++;

            $mainModule->addMainModuleEvent(
                (new MainModuleEvent())
                    ->setTitle(sprintf('Sous module %s n°%d', $this->module->getName(), $nbSubEvents))
                    ->setTitle('')
                    ->setStart($startAt)
                    ->setEnd($endAt)
                    ->setIsMainEvent(false)
                    ->setOccurenceId($mainModule->getOccurenceId())
                    ->setModuleId($mainModule->getModuleId()),
            );
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