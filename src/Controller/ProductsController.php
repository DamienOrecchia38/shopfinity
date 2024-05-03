<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Trait\CartTrait;

#[Route('/products')]
class ProductsController extends AbstractController
{
    use CartTrait;

    #[Route('/{id}', name: 'app_products_show', methods: ['GET'])]
    public function show(Products $product, Request $request): Response
    {
        $cartQuantity = $this->cartQuantity($request);

        return $this->render('products/show.html.twig', [
            'product' => $product,
            'cartQuantity' => $cartQuantity,
        ]);
    }
}
