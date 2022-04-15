<?php
namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Tag;
use App\Entity\Topic;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MusicOne');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Evénement');
        yield MenuItem::linkToCrud('Evénements', 'fas fa-handshake', Event::class);

        yield MenuItem::section('Nomenclature');
        yield MenuItem::linkToCrud('Lieux', 'fas fa-map-marker-alt', Place::class);
        yield MenuItem::linkToCrud('Tags', 'fas fa-tags', Tag::class);
        yield MenuItem::linkToCrud('Topic', 'fas fa-tags', Topic::class);
    }
}
