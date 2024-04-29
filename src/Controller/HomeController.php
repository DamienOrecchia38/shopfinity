<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    public function cartQuantity(Request $request): int
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);
        $cartQuantity = 0;
        foreach ($cart as $quantity) {
            $cartQuantity += $quantity;
        }

        return $cartQuantity;
    }

    #[Route('/home', name: 'app_home')]
    public function index(ProductsRepository $productsRepository, Request $request): Response
    {
        $cartQuantity = $this->cartQuantity($request);

        return $this->render('home/home.html.twig', [
            'products' => $productsRepository->findAll(),
            'cartQuantity' => $cartQuantity,
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'app_add_to_cart', methods: ['POST'])]
    public function addToCart(int $id, Request $request, ProductsRepository $productsRepository): Response
    {
        $cartQuantity = $this->cartQuantity($request);
        $session = $request->getSession();
        $cart = $session->get('cart', []);
    
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
        $cart[$id]++;
    
        $session->set('cart', $cart);
    
        return $this->render('home/home.html.twig', [
            'cart' => $cart,
            'cartQuantity' => $cartQuantity,
            'products' => $productsRepository->findAll(),
        ]);
    }
}
