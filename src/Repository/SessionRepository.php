<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function findMostPopularSessions(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT s.title, COUNT(sm.member_id) AS howMany 
                FROM session_member sm 
                INNER JOIN session s ON s.id = sm.session_id
                GROUP BY s.id
                ORDER BY howMany DESC";
        return $conn->executeQuery($sql)->fetchAllAssociative();
    }
}