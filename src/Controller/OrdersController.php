<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Products;
use App\Form\OrdersType;
use App\Repository\OrdersRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoriesRepository;
use App\Trait\CartTrait;

#[Route('/orders')]
class OrdersController extends AbstractController implements EventSubscriberInterface 
{
    use CartTrait;
    
    #[Route('/cart', name: 'app_cart_show', methods: ['GET'])]
    public function showCart(Request $request, EntityManagerInterface $entityManager, CategoriesRepository $categoriesRepository): Response
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

        $cartQuantity = $this->cartQuantity($request);
        $categories = $categoriesRepository->findAll();

        return $this->render('orders/cart.html.twig', [
            'products' => $products,
            'priceTotal' => $priceTotal,
            'cartQuantity' => $cartQuantity,
            'categories' => $categories,
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

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['onOrderCreated'],
        ];
    }

    public function onOrderCreated(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        
        if ($entity instanceof Orders) {
            $entity->setCreatedAt(new \DateTime());
        }
    }
}
