<?php

namespace App\Model\Module;

use App\Entity\Module\Module;
use App\Entity\Module\Schedule;

class MainModuleEvent
{
    private string    $title;
    private \DateTime $start;
    private \DateTime $end;
    private bool      $isMainEvent;
    private string    $occurenceId;
    private int       $moduleId;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): MainModuleEvent
    {
        $this->title = $title;
        return $this;
    }

    public function getStart(): \DateTime
    {
        return $this->start;
    }

    public function setStart(\DateTime $start): MainModuleEvent
    {
        $this->start = $start;
        return $this;
    }

    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end): MainModuleEvent
    {
        $this->end = $end;
        return $this;
    }

    public function isMainEvent(): bool
    {
        return $this->isMainEvent;
    }

    public function setIsMainEvent(bool $isMainEvent): MainModuleEvent
    {
        $this->isMainEvent = $isMainEvent;
        return $this;
    }

    public function getOccurenceId(): string
    {
        return $this->occurenceId;
    }

    public function setOccurenceId(string $occurenceId): MainModuleEvent
    {
        $this->occurenceId = $occurenceId;
        return $this;
    }

    public function getModuleId(): int
    {
        return $this->moduleId;
    }

    public function setModuleId(int $moduleId): MainModuleEvent
    {
        $this->moduleId = $moduleId;
        return $this;
    }
}