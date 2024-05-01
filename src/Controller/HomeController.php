<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductsRepository;
use App\Repository\CategoriesRepository;
use App\Trait\CartTrait;
use App\Trait\ProductsListTrait;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    use CartTrait;
    use ProductsListTrait;

    #[Route('/home', name: 'app_home')]
    public function index(CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $cartQuantity = $this->cartQuantity($request);
        $categories = $categoriesRepository->findAll();
        $productsList = $this->productsList($productsRepository, $paginator, $request);

        return $this->render('home/home.html.twig', [
            'cartQuantity' => $cartQuantity,
            'categories' => $categories,
            'products' => $productsList,
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'app_add_to_cart', methods: ['POST'])]
    public function addToCart(int $id, Request $request, CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository, PaginatorInterface $paginator): Response
    {
        $cartQuantity = $this->cartQuantity($request);
        $categories = $categoriesRepository->findAll();
        $productsList = $this->productsList($productsRepository, $paginator, $request);
        $session = $request->getSession();
        $cart = $session->get('cart', []);
    
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
        $cart[$id]++;
    
        $session->set('cart', $cart);
    
        return $this->render('home/home.html.twig', [
            'cartQuantity' => $cartQuantity,
            'categories' => $categories,
            'products' => $productsList,
            'cart' => $cart,
        ]);
    }
}
