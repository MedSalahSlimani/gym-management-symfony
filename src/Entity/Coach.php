<?php

namespace App\Entity;

use App\Repository\CoachRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoachRepository::class)]
class Coach
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    private ?string $specialty = null;

    #[ORM\ManyToMany(targetEntity: Session::class, mappedBy: 'coaches')]
    private Collection $sessions;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getSurname(): ?string { return $this->surname; }
    public function setSurname(string $surname): static { $this->surname = $surname; return $this; }
    public function getSpecialty(): ?string { return $this->specialty; }
    public function setSpecialty(string $specialty): static { $this->specialty = $specialty; return $this; }
    public function getSessions(): Collection { return $this->sessions; }

// src/Entity/Coach.php
public function __toString(): string
{
    return $this->name . ' ' . $this->surname;
}
    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->addCoach($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            $session->removeCoach($this);
        }

        return $this;
    }
}