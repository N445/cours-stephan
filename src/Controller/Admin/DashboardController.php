<?php

namespace App\Controller\Admin;

use App\Entity\Module\Module;
use App\Entity\Module\Planning;
use App\Entity\Module\SubModule;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/%admin_base_path%', name: 'ADMIN_DASHBOARD')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
                        ->setTitle('Cours Stephan')
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Module');
        yield MenuItem::linkToCrud('Planning', 'fa fa-home', Planning::class);
        yield MenuItem::linkToCrud('Module', 'fa fa-home', Module::class);
        yield MenuItem::linkToCrud('Sous module', 'fa fa-home', SubModule::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
