<?php

namespace App\Trait;

use App\Repository\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

trait ProductsListTrait {
    public function productsList(ProductsRepository $productsRepository, PaginatorInterface $paginator, Request $request): object
    {
        $data = $productsRepository->findAll();
        $productsList = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
        );

        return $productsList;
    }
}