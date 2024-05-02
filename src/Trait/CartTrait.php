<?php

namespace App\Trait;

use App\Entity\Products;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

trait CartTrait {
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

    public function priceTotal(Request $request, EntityManagerInterface $entityManager): float
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


        return $priceTotal;
    }
}