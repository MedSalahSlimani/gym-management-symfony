<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?bool $isPaid = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $member = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plan $plan = null;

    public function getId(): ?int { return $this->id; }
    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(\DateTimeInterface $startDate): static { $this->startDate = $startDate; return $this; }
    public function getEndDate(): ?\DateTimeInterface { return $this->endDate; }
    public function setEndDate(\DateTimeInterface $endDate): static { $this->endDate = $endDate; return $this; }
    public function isIsPaid(): ?bool { return $this->isPaid; }
    public function setIsPaid(bool $isPaid): static { $this->isPaid = $isPaid; return $this; }
    public function getMember(): ?Member { return $this->member; }
    public function setMember(?Member $member): static { $this->member = $member; return $this; }
    public function getPlan(): ?Plan { return $this->plan; }
    public function setPlan(?Plan $plan): static { $this->plan = $plan; return $this; }

    public function isPaid(): ?bool
    {
        return $this->isPaid;
    }
}