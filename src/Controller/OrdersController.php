<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Products;
use App\Form\OrdersType;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/orders')]
class OrdersController extends AbstractController
{
    
    #[Route('/cart', name: 'app_cart_show', methods: ['GET'])]
    public function showCart(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        $products = [];
        $productRepository = $entityManager->getRepository(Products::class);
        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                $products[] = ['product' => $product, 'quantity' => $quantity];
            }
        }

        $priceTotal = 0;
        foreach ($products as $item) {
            $priceTotal += $item['product']->getPrice() * $item['quantity'];
        }

        $cartQuantity = 0;
        foreach ($cart as $quantity) {
            $cartQuantity += $quantity;
        }

        return $this->render('orders/cart.html.twig', [
            'products' => $products,
            'priceTotal' => $priceTotal,
            'cartQuantity' => $cartQuantity,
        ]);
    }

    #[Route('/cart/remove/{id}', name: 'app_remove_from_cart', methods: ['POST'])]
    public function removeFromCart(int $id, Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('app_cart_show');
    }

    // #[Route('/new', name: 'app_orders_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $order = new Orders();
    //     $form = $this->createForm(OrdersType::class, $order);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($order);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_orders_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('orders/new.html.twig', [
    //         'order' => $order,
    //         'form' => $form,
    //     ]);
    // }
}
