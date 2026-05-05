<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function countMembersPerPlan(): array
    {
        return $this->createQueryBuilder('s')
            ->select('p.name AS plan_name, COUNT(s.id) AS total')
            ->join('s.plan', 'p')
            ->groupBy('p.id')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getResult();
    }
}