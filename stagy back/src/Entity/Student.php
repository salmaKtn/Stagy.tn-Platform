<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[Broadcast]
class Student extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $level = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fieldOfStudy = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $URLs = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cvFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\OneToOne(mappedBy: 'student', cascade: ['persist', 'remove'])]
    private ?CV $cv = null;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'student')]
    private Collection $applications;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $birthady = null;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
    }

    public function getfirstName(): ?string
    {
        return $this->firstName;
    }

    public function setfirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getlastName(): ?string
    {
        return $this->lastName;
    }

    public function setlastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }
    public function getFullName(): string
{
    return $this->firstName . ' ' . $this->lastName;
}

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setLevel(?string $level): static
    {
        $this->level = $level;

        return $this;
    }
    public function getfieldOfStudy(): ?string
    {
        return $this->fieldOfStudy;
    }

    public function setfieldOfStudy(string $fieldOfStudy): static
    {
        $this->fieldOfStudy = $fieldOfStudy;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getURLs(): ?array
    {
        return $this->URLs;
    }

    public function setURLs(?array $URLs): static
    {
        $this->URLs = $URLs;

        return $this;
    }

    public function getCvFile(): ?string
    {
        return $this->cvFile;
    }

    public function setCvFile(?string $cvFile): static
    {
        $this->cvFile = $cvFile;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCv(): ?CV
    {
        return $this->cv;
    }

    public function setCv(?CV $cv): static
    {
        // unset the owning side of the relation if necessary
        if (null === $cv && null !== $this->cv) {
            $this->cv->setStudent(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $cv && $cv->getStudent() !== $this) {
            $cv->setStudent($this);
        }

        $this->cv = $cv;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setStudent($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getStudent() === $this) {
                $application->setStudent(null);
            }
        }

        return $this;
    }

    public function getBirthady(): ?\DateTime
    {
        return $this->birthady;
    }

    public function setBirthady(?\DateTime $birthady): static
    {
        $this->birthady = $birthady;

        return $this;
    }
}
