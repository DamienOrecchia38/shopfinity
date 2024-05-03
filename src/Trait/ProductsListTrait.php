<?php

namespace App\Trait;

use App\Repository\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

trait ProductsListTrait {
    public function productsList(ProductsRepository $productsRepository, PaginatorInterface $paginator, Request $request, ?int $categoryId = null)
    {
        $queryBuilder = $productsRepository->createQueryBuilder('p');
        if ($categoryId) {
            $queryBuilder->andWhere('p.categories = :categoryId')->setParameter('categoryId', $categoryId);
        }
        $query = $queryBuilder->getQuery();
    
        $productsList = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            8
        );
    
        return $productsList;
    }
}