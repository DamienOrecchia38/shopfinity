<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductsRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ProductsRepository $productsRepository): Response
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $productsRepository->findAll()
        ]);
    }
}
