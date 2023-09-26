<?php

namespace App\Entity\Module;

use App\Repository\Module\ModuleRepository;
use Carbon\CarbonInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
#[UniqueEntity('name')]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: SubModule::class,cascade: ['persist','remove'])]
    private Collection $subModules;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: Schedule::class,cascade: ['persist','remove'])]
    private Collection $schedules;

    public function __construct()
    {
        $this->subModules = new ArrayCollection();
        $this->schedules = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, SubModule>
     */
    public function getSubModules(): Collection
    {
        return $this->subModules;
    }

    public function addSubModule(SubModule $subModule): static
    {
        if (!$this->subModules->contains($subModule)) {
            $this->subModules->add($subModule);
            $subModule->setModule($this);
        }

        return $this;
    }

    public function removeSubModule(SubModule $subModule): static
    {
        if ($this->subModules->removeElement($subModule)) {
            // set the owning side to null (unless already changed)
            if ($subModule->getModule() === $this) {
                $subModule->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getTotalDuration(): string
    {
        $total = CarbonInterval::create(0,0,0,0,0,0,0);
        dump($total);

        /** @var Schedule $schedule */
        foreach ($this->getSchedules() as $schedule) {
            $total->add($schedule->getDuration());
        }
        return $total->forHumans(['short' => true]);
    }

    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): static
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setModule($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getModule() === $this) {
                $schedule->setModule(null);
            }
        }

        return $this;
    }
}
