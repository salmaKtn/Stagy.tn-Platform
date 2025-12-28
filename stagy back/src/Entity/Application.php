<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[Broadcast]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $compatibility = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $timestamp = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    private ?Offer $offer = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    private ?Student $student = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompatibility(): ?float
    {
        return $this->compatibility;
    }

    public function setCompatibility(?float $compatibility): static
    {
        $this->compatibility = $compatibility;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function setTimestamp(?string $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): static
    {
        $this->offer = $offer;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }
}
