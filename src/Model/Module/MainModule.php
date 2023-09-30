<?php

namespace App\Model\Module;

use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use Doctrine\Common\Collections\ArrayCollection;

class MainModule
{
    private string $title;
    private \DateTime $start;
    private \DateTime $end;
    private int $moduleId;
    private string $occurenceId;
    private bool $available;
    private int $nbPlaces;
    private ArrayCollection $mainModuleEvents;

    public function __construct()
    {
        $this->mainModuleEvents = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->occurenceId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): MainModule
    {
        $this->title = $title;
        return $this;
    }

    public function getStart(): \DateTime
    {
        return $this->start;
    }

    public function setStart(\DateTime $start): MainModule
    {
        $this->start = $start;
        return $this;
    }

    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end): MainModule
    {
        $this->end = $end;
        return $this;
    }

    public function getModuleId(): int
    {
        return $this->moduleId;
    }

    public function setModuleId(int $moduleId): MainModule
    {
        $this->moduleId = $moduleId;
        return $this;
    }

    public function getOccurenceId(): string
    {
        return $this->occurenceId;
    }

    public function setOccurenceId(string $occurenceId): MainModule
    {
        $this->occurenceId = $occurenceId;
        return $this;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): MainModule
    {
        $this->available = $available;
        return $this;
    }

    public function getNbPlaces(): int
    {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): MainModule
    {
        $this->nbPlaces = $nbPlaces;
        return $this;
    }

    public function getMainModuleEvents(): ArrayCollection
    {
        return $this->mainModuleEvents;
    }

    public function addMainModuleEvent(MainModuleEvent $mainModuleEvent): MainModule
    {
        $this->mainModuleEvents->add($mainModuleEvent);
        return $this;
    }
}