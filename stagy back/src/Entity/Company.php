<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[Broadcast]
class Company extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoURL = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverURL = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slogan = null;

   

    #[ORM\Column(nullable: true)]
    private ?int $taxNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $field = null;

    #[ORM\Column(nullable: true)]
    private ?array $socialMediaURLs = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $agentName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $agentLastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $agentEmail = null;

    #[ORM\Column(nullable: true)]
    private ?bool $validation = null;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'company')]
    private Collection $offers;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about = null;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getLogoURL(): ?string
    {
        return $this->logoURL;
    }

    public function setLogoURL(?string $logoURL): static
    {
        $this->logoURL = $logoURL;

        return $this;
    }

    public function getCoverURL(): ?string
    {
        return $this->coverURL;
    }

    public function setCoverURL(?string $coverURL): static
    {
        $this->coverURL = $coverURL;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): static
    {
        $this->slogan = $slogan;

        return $this;
    }

   
    public function getTaxNumber(): ?int
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(?int $taxNumber): static
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function setField(?string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function getSocialMediaURLs(): ?array
    {
        return $this->socialMediaURLs;
    }

    public function setSocialMediaURLs(?array $socialMediaURLs): static
    {
        $this->socialMediaURLs = $socialMediaURLs;

        return $this;
    }

    public function getAgentName(): ?string
    {
        return $this->agentName;
    }

    public function setAgentName(?string $agentName): static
    {
        $this->agentName = $agentName;

        return $this;
    }

    public function getAgentLastname(): ?string
    {
        return $this->agentLastname;
    }

    public function setAgentLastname(?string $agentLastname): static
    {
        $this->agentLastname = $agentLastname;

        return $this;
    }

    public function getAgentEmail(): ?string
    {
        return $this->agentEmail;
    }

    public function setAgentEmail(?string $agentEmail): static
    {
        $this->agentEmail = $agentEmail;

        return $this;
    }

    public function isValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(?bool $validation): static
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setCompany($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getCompany() === $this) {
                $offer->setCompany(null);
            }
        }

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): static
    {
        $this->about = $about;

        return $this;
    }
}
