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

class ModuleEvents
{
    private Schedule $schedule;
    private Module $module;

    private ArrayCollection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(Schedule $schedule): ModuleEvents
    {
        $this->schedule = $schedule;
        return $this;
    }

    public function getModule(): Module
    {
        return $this->module;
    }

    public function setModule(Module $module): ModuleEvents
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @param string|null $occurenceId
     * @return MainModule[]
     */
    public function getEvents(?string $occurenceId = null): array
    {
        if ($occurenceId) {
            return array_filter($this->events->toArray(), static function (MainModule $moduleEvent) use ($occurenceId) {
                return $moduleEvent->getOccurenceId() === $occurenceId;
            });
        }
        return $this->events->toArray();
    }

    /**
     * @return MainModule[]
     * @throws ExceptionInterface
     */
    public function getEventsArray(): array
    {
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, string $format, array $context): string {
                return $object->getId();
            },
        ];

        return (new Serializer([new DateTimeNormalizer(),new ObjectNormalizer(defaultContext: $defaultContext)], []))
            ->normalize(
                $this->getEvents(),
                'array'
            );
    }

    public function addEvent(MainModule $event): ModuleEvents
    {
        $this->events->add($event);
        return $this;
    }

    public function removeEvent(MainModule $event): ModuleEvents
    {
//        $this->events->remove($event->getOccurenceId());
//        dump($this->events);
//        die;
        if ($this->events->contains($event)){
            $this->events->remove($event);
        }
        return $this;
    }
}