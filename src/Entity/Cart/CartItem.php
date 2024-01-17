<?php

namespace App\Entity\Cart;

use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use App\Model\Module\MainModule;
use App\Repository\Cart\CartItemRepository;
use App\Service\Cart\CartItemLocationHelper;
use App\Service\Cart\CartPriceHelper;
use App\Service\Cart\CartValidator;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    private ?Module $module      = null;
    #[ORM\Column(length: 255)]
    private ?int    $moduleId    = null;
    #[ORM\Column(length: 255)]
    private ?string $moduleName  = null;
    #[ORM\Column(length: 255)]
    private ?string $occurenceId = null;

    #[ORM\Column(length: 255)]
    private ?\DateTime $moduleDateTime = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    private ?Cart $cart = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int    $quantity = null;
    #[ORM\Column(nullable: true)]
    private ?string $location = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    private ?Schedule $schedule = null;

    #[ORM\Column]
    private ?int $scheduleId = null;

    #[ORM\Column(length: 255)]
    private ?string $scheduleName = null;

    #[ORM\Column(type: Types::OBJECT)]
    private ?MainModule $mainModule = null;

    #[ORM\Column]
    private array $subModules = [];

    public function __toString(): string
    {
        return sprintf('%s : Du %s au %s',
                       $this->getModuleName(),
                       $this->getMainModule()->getStart()->format('d/m/Y H:i'),
                       $this->getMainModule()->getEnd()->format('d/m/Y H:i'),
        );
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getModuleId(): ?int
    {
        return $this->moduleId;
    }

    public function setModuleId(?int $moduleId): CartItem
    {
        $this->moduleId = $moduleId;
        return $this;
    }

    public function getModuleName(): ?string
    {
        return $this->moduleName;
    }

    public function setModuleName(string $moduleName): static
    {
        $this->moduleName = $moduleName;

        return $this;
    }

    public function getOccurenceId(): ?string
    {
        return $this->occurenceId;
    }

    public function setOccurenceId(?string $occurenceId): CartItem
    {
        $this->occurenceId = $occurenceId;
        return $this;
    }

    public function getModuleDateTime(): ?\DateTime
    {
        return $this->moduleDateTime;
    }

    public function setModuleDateTime(?\DateTime $moduleDateTime): CartItem
    {
        $this->moduleDateTime = $moduleDateTime;
        return $this;
    }

    public function getStartAt(): ?\DateTime
    {
        return $this->moduleDateTime;
    }

    public function getEndAt(): ?\DateTime
    {
        return (clone $this->moduleDateTime)->add(new \DateInterval('P3DT1H30M'));
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): CartItem
    {
        $this->location = $location;
        return $this;
    }

    public function isLocationProfessor(): bool
    {
        return $this->location === CartItemLocationHelper::PROFESSOR;
    }

    public function isLocationHote(): bool
    {
        return $this->location === CartItemLocationHelper::HOTE;
    }

    public function isLocationVisio(): bool
    {
        return $this->location === CartItemLocationHelper::VISIO;
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

    public function getScheduleId(): ?int
    {
        return $this->scheduleId;
    }

    public function setScheduleId(int $scheduleId): static
    {
        $this->scheduleId = $scheduleId;

        return $this;
    }

    public function getScheduleName(): ?string
    {
        return $this->scheduleName;
    }

    public function setScheduleName(string $scheduleName): static
    {
        $this->scheduleName = $scheduleName;

        return $this;
    }

    public function getMainModule(): ?MainModule
    {
        return $this->mainModule;
    }

    public function setMainModule(MainModule $mainModule): static
    {
        $this->mainModule = $mainModule;

        return $this;
    }

    public function getSubModules(): array
    {
        return $this->subModules;
    }

    public function setSubModules(array $subModules): static
    {
        $this->subModules = $subModules;

        return $this;
    }

    public function getTotal(): ?int
    {
        return CartPriceHelper::getCartItemPrice($this);
    }

    public function hasLocationPromo(): bool
    {
        return CartPriceHelper::cartItemHasLocationPromo($this);
    }

    public function isValid(): bool
    {
        return CartValidator::cartItemIsValid($this);
    }
}
