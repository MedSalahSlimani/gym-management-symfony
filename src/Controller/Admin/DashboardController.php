<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(MemberCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('🏋️ Gym Management')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        $urlGenerator = $this->container->get(AdminUrlGenerator::class);
        
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Members', 'fas fa-users', 'admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => MemberCrudController::class,
        ]);
        yield MenuItem::linkToRoute('Coaches', 'fas fa-chalkboard-user', 'admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => CoachCrudController::class,
        ]);
        yield MenuItem::linkToRoute('Plans', 'fas fa-clipboard-list', 'admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => PlanCrudController::class,
        ]);
        yield MenuItem::linkToRoute('Sessions', 'fas fa-dumbbell', 'admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => SessionCrudController::class,
        ]);
        yield MenuItem::linkToRoute('Subscriptions', 'fas fa-credit-card', 'admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => SubscriptionCrudController::class,
        ]);
    }
}