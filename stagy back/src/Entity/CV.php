<?php

namespace App\Entity;

use App\Repository\CVRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CVRepository::class)]
class CV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cv:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'cv', cascade: ['persist', 'remove'])]
    #[Groups(['cv:read', 'cv:write'])]
    private ?Student $student = null;

    /**
     * @var Collection<int, SectionEntry>
     */
    #[ORM\OneToMany(targetEntity: SectionEntry::class, mappedBy: 'cv')]
    private Collection $sectionEntries;

    public function __construct()
    {
        $this->sectionEntries = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        // Prevent unnecessary changes
        if ($this->student === $student) {
            return $this;
        }

        // Handle bidirectional relationship
        if (null !== $this->student) {
            $this->student->setCv(null);
        }

        $this->student = $student;

        if (null !== $student) {
            $student->setCv($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SectionEntry>
     */
    public function getSectionEntries(): Collection
    {
        return $this->sectionEntries;
    }

    public function addSectionEntry(SectionEntry $sectionEntry): static
    {
        if (!$this->sectionEntries->contains($sectionEntry)) {
            $this->sectionEntries->add($sectionEntry);
            $sectionEntry->setCv($this);
        }

        return $this;
    }

    public function removeSectionEntry(SectionEntry $sectionEntry): static
    {
        if ($this->sectionEntries->removeElement($sectionEntry)) {
            // set the owning side to null (unless already changed)
            if ($sectionEntry->getCv() === $this) {
                $sectionEntry->setCv(null);
            }
        }

        return $this;
    }

    
}
