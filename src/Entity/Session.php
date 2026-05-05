<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $sessionDate = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\ManyToMany(targetEntity: Coach::class, inversedBy: 'sessions')]
    private Collection $coaches;

    #[ORM\ManyToMany(targetEntity: Member::class, inversedBy: 'sessions')]
    private Collection $members;

    public function __construct()
    {
        $this->coaches = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getSessionDate(): ?\DateTimeInterface { return $this->sessionDate; }
    public function setSessionDate(\DateTimeInterface $sessionDate): static { $this->sessionDate = $sessionDate; return $this; }
    public function getDuration(): ?int { return $this->duration; }
    public function setDuration(int $duration): static { $this->duration = $duration; return $this; }
    public function getCapacity(): ?int { return $this->capacity; }
    public function setCapacity(int $capacity): static { $this->capacity = $capacity; return $this; }
    public function getCoaches(): Collection { return $this->coaches; }
    public function getMembers(): Collection { return $this->members; }

    public function __toString(): string
    {
        return $this->title . ' (' . $this->sessionDate->format('Y-m-d H:i') . ')';
    }

    public function addCoach(Coach $coach): static
    {
        if (!$this->coaches->contains($coach)) {
            $this->coaches->add($coach);
        }

        return $this;
    }

    public function removeCoach(Coach $coach): static
    {
        $this->coaches->removeElement($coach);

        return $this;
    }

    public function addMember(Member $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(Member $member): static
    {
        $this->members->removeElement($member);

        return $this;
    }
}