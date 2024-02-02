<?php

namespace App\Entity;

use App\Repository\User\InformationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InformationRepository::class)]
class Information
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max       : 255,
        maxMessage: 'Doit faire moins de {{ limit }} caractères.',
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max       : 255,
        maxMessage: 'Doit faire moins de {{ limit }} caractères.',
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 38, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max       : 38,
        maxMessage: 'Doit faire moins de {{ limit }} caractères.',
    )]
    private ?string $address1 = null;

    #[ORM\Column(length: 38, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max       : 38,
        maxMessage: 'Doit faire moins de {{ limit }} caractères.',
    )]
    private ?string $address2 = null;

    #[ORM\Column(length: 38, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max       : 38,
        maxMessage: 'Doit faire moins de {{ limit }} caractères.',
    )]
    private ?string $address3 = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max       : 50,
        maxMessage: 'Doit faire moins de {{ limit }} caractères.',
    )]
    private ?string $postCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        max       : 255,
        maxMessage: 'Doit faire moins de {{ limit }} caractères.',
    )]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullAddress(): string
    {
        return sprintf(
            "%s\n%s\n%s\n%s %s\n%s",
            $this->getAddress1(),
            $this->getAddress2(),
            $this->getAddress3(),
            $this->getPostCode(),
            $this->getCity(),
            $this->getCountry(),
        );
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): Information
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): static
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): static
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getAddress3(): ?string
    {
        return $this->address3;
    }

    public function setAddress3(?string $address3): static
    {
        $this->address3 = $address3;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): static
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }
}
