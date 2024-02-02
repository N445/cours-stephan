<?php

namespace App\Entity\Cart;

use App\Entity\Information;
use App\Entity\User;
use App\Repository\Cart\CartRepository;
use App\Service\Cart\CartPriceHelper;
use App\Service\Cart\CartValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[Gedmo\Loggable]
class Cart
{
    public const PLACE_CART      = 'cart';
    public const PLACE_PENDING   = 'pending';
    public const PLACE_CANCELLED = 'cancelled';
    public const PLACE_COMPLETE  = 'complete';

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $anonymousToken = null;

    #[ORM\Column(length: 60)]
    private ?string $place = 'cart';

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    #[Gedmo\Blameable(on: 'create')]
    private $createdBy;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'updated_by', referencedColumnName: 'id')]
    #[Gedmo\Blameable(on: 'update')]
    private $updatedBy;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(["moduleDateTime" => "ASC"])]
    private Collection $cartItems;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $methodPayment = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    private ?User $user = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Information $information = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $payedAt = null;

    public function __construct()
    {
        $this->cartItems   = new ArrayCollection();
        $this->information = new Information();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnonymousToken(): ?string
    {
        return $this->anonymousToken;
    }

    public function setAnonymousToken(?string $anonymousToken): static
    {
        $this->anonymousToken = $anonymousToken;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function isPaid(): bool
    {
        return $this->place === 'complete';
    }


    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }


    public function sortCartItems(): static
    {
        $cartItems = $this->cartItems->toArray();

        usort($cartItems, static fn(CartItem $a, CartItem $b) => $a->getModuleDateTime() > $b->getModuleDateTime());
        $this->cartItems = new ArrayCollection($cartItems);

        return $this;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setCart($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getCart() === $this) {
                $cartItem->setCart(null);
            }
        }

        return $this;
    }

    public function getMethodPayment(): ?string
    {
        return $this->methodPayment;
    }

    public function setMethodPayment(?string $methodPayment): static
    {
        $this->methodPayment = $methodPayment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getInformation(): ?Information
    {
        return $this->information;
    }

    public function setInformation(?Information $information): Cart
    {
        $this->information = $information;
        return $this;
    }

    public function getTotal(): ?int
    {
        return CartPriceHelper::getCartPrice($this);
    }

    public function isValid(): bool
    {
        return CartValidator::cartIsValid($this);
    }

    public function getPayedAt(): ?\DateTime
    {
        return $this->payedAt;
    }

    public function setPayedAt(?\DateTime $payedAt): Cart
    {
        $this->payedAt = $payedAt;
        return $this;
    }
}
