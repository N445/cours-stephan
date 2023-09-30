<?php

namespace App\Entity\Module;

use App\Repository\Module\PlanningRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $mondayTimes = [];

    #[ORM\Column]
    private array $wenesdayTimes = [];

    #[ORM\Column]
    private array $fridayTimes = [];

    #[ORM\ManyToOne(inversedBy: 'plannings')]
    private ?Module $module = null;

    #[ORM\ManyToOne(inversedBy: 'plannings')]
    private ?Schedule $schedule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMondayTimes(): array
    {
        return $this->mondayTimes;
    }

    public function setMondayTimes(array $mondayTimes): static
    {
        $this->mondayTimes = $mondayTimes;

        return $this;
    }

    public function getWenesdayTimes(): array
    {
        return $this->wenesdayTimes;
    }

    public function setWenesdayTimes(array $wenesdayTimes): static
    {
        $this->wenesdayTimes = $wenesdayTimes;

        return $this;
    }

    public function getFridayTimes(): array
    {
        return $this->fridayTimes;
    }

    public function setFridayTimes(array $fridayTimes): static
    {
        $this->fridayTimes = $fridayTimes;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

        return $this;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }
}
