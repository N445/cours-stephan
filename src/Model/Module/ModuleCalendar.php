<?php

namespace App\Model\Module;

use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ModuleCalendar
{
    private Schedule $schedule;
    private Module $module;

    private ArrayCollection $mainModules;

    public function __construct()
    {
        $this->mainModules = new ArrayCollection();
    }

    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(Schedule $schedule): ModuleCalendar
    {
        $this->schedule = $schedule;
        return $this;
    }

    public function getModule(): Module
    {
        return $this->module;
    }

    public function setModule(Module $module): ModuleCalendar
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @param string $occurenceId
     * @return ?MainModule
     */
    public function getMainModuleByOccurenceId(string $occurenceId): ?MainModule
    {
        return $this->mainModules->get($occurenceId);
    }

    /**
     * @return ArrayCollection<int, MainModule>
     */
    public function getMainModules(): ArrayCollection
    {
        return $this->mainModules;
    }

    public function addMainModules(MainModule $mainModule): ModuleCalendar
    {
        $this->mainModules->set((string)$mainModule, $mainModule);
        return $this;
    }

    public function removeMainModules(MainModule $mainModule): ModuleCalendar
    {
        if ($this->mainModules->contains($mainModule)) {
            $this->mainModules->remove($mainModule);
        }
        return $this;
    }

    public function getEvents(): array
    {
        $events = [];
        foreach ($this->getMainModules() as $mainModule) {
            $events = array_merge($events, $mainModule->getMainModuleEvents()->toArray());
        }

        return $events;
    }

    public function getCalendarEvents(): array
    {
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, string $format, array $context): string {
                return $object->getId();
            },
        ];

        $events = $this->getEvents();

        return (new Serializer([new DateTimeNormalizer(), new ObjectNormalizer(defaultContext: $defaultContext)], []))
            ->normalize(
                $events,
                'array'
            );
    }
}