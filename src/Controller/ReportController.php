<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use App\Repository\SubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report/popular-sessions', name: 'popular_sessions')]
    public function popularSessions(SessionRepository $repository): Response
    {
        return $this->render('report/popular_sessions.html.twig', [
            'sessions' => $repository->findMostPopularSessions(),
        ]);
    }

    #[Route('/report/members-per-plan', name: 'members_per_plan')]
    public function membersPerPlan(SubscriptionRepository $repository): Response
    {
        return $this->render('report/members_per_plan.html.twig', [
            'stats' => $repository->countMembersPerPlan(),
        ]);
    }
}