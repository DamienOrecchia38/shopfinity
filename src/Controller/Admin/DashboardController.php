<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Controller\Admin\UsersCrudController;
use App\Entity\Users;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\Categories;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UsersCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Shopfinity');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoRoute('Retour au site', 'fas fa-arrow-left', 'app_home');
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-user', Users::class);
        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commandes', 'fa fa-file text', Orders::class);
        yield MenuItem::section('Produits');
        yield MenuItem::linkToCrud('Produits', 'fas fa-tags', Products::class);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-file text', Categories::class);
    }
}