<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
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
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $joinDate = null;

    #[ORM\ManyToMany(targetEntity: Session::class, mappedBy: 'members')]
    private Collection $sessions;

    #[ORM\OneToMany(mappedBy: 'member', targetEntity: Subscription::class)]
    private Collection $subscriptions;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->joinDate = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getSurname(): ?string { return $this->surname; }
    public function setSurname(string $surname): static { $this->surname = $surname; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(string $phone): static { $this->phone = $phone; return $this; }
    public function getJoinDate(): ?\DateTimeInterface { return $this->joinDate; }
    public function setJoinDate(\DateTimeInterface $joinDate): static { $this->joinDate = $joinDate; return $this; }
    public function getSessions(): Collection { return $this->sessions; }
    public function getSubscriptions(): Collection { return $this->subscriptions; }

    public function __toString(): string
    {
        return $this->name . ' ' . $this->surname;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->addMember($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            $session->removeMember($this);
        }

        return $this;
    }

    public function addSubscription(Subscription $subscription): static
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setMember($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): static
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getMember() === $this) {
                $subscription->setMember(null);
            }
        }

        return $this;
    }
}