<?php

namespace App\Controller\Admin;

use App\Entity\Blog\Blog;
use App\Entity\Cart\Cart;
use App\Entity\Contact;
use App\Entity\Module\Module;
use App\Entity\Module\Schedule;
use App\Entity\Module\SubModule;
use App\Entity\Testimonial\Testimonial;
use App\Entity\User;
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
                        ->setTitle('Cours Stéphan')
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Module');
        yield MenuItem::linkToCrud('Planning', 'fa-regular fa-calendar-days', Schedule::class);
        yield MenuItem::linkToCrud('Module', 'fa-solid fa-graduation-cap', Module::class);
        yield MenuItem::linkToCrud('Sous module', 'fa-brands fa-leanpub', SubModule::class);
        yield MenuItem::subMenu('Contenu');
        yield MenuItem::linkToCrud('Blog', 'fa-solid fa-newspaper', Blog::class);

        yield MenuItem::subMenu('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Paniers', 'fa-solid fa-cart-shopping', Cart::class);
        yield MenuItem::linkToCrud('Contact', 'fa-solid fa-message', Contact::class);
        yield MenuItem::linkToCrud('Testimonial', 'fa-solid fa-comment', Testimonial::class);

    }
}
