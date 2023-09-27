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

    #[ORM\ManyToMany(targetEntity: SubModule::class, inversedBy: 'modules')]
    private Collection $subModules;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: Planning::class)]
    private Collection $plannings;

    public function __construct()
    {
        $this->subModules = new ArrayCollection();
        $this->plannings  = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeSubModule(SubModule $subModule): static
    {
        $this->subModules->removeElement($subModule);

        return $this;
    }

    /**
     * @param Shedule $shedule
     *
     * @return Planning|null
     */
    public function getPlanningByShedule(Shedule $shedule): ?Planning
    {
        return array_filter($this->plannings->toArray(), static function (Planning $planning) use ($shedule) {
            return $planning->getShedule() === $shedule;
        })[0] ?? null;
    }

    /**
     * @return Collection<int, Planning>
     */
    public function getPlannings(): Collection
    {
        return $this->plannings;
    }

    public function addPlanning(Planning $planning): static
    {
        if (!$this->plannings->contains($planning)) {
            $this->plannings->add($planning);
            $planning->setModule($this);
        }

        return $this;
    }

    public function removePlanning(Planning $planning): static
    {
        if ($this->plannings->removeElement($planning)) {
            // set the owning side to null (unless already changed)
            if ($planning->getModule() === $this) {
                $planning->setModule(null);
            }
        }

        return $this;
    }
}
