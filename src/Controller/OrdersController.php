<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Trait\CartTrait;

#[Route('/orders')]
class OrdersController extends AbstractController implements EventSubscriberInterface 
{
    use CartTrait;
    
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

        $priceTotal = $this->priceTotal($request, $entityManager);
        $cartQuantity = $this->cartQuantity($request);

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

     #[Route('/create', name: 'app_orders_create', methods: ['POST'])]
    public function createOrder(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quantity = $this->cartQuantity($request);
        $priceTotal = $this->priceTotal($request, $entityManager);

        $order = new Orders();
        $order->setCreatedAt(new \DateTime());
        $order->setUsers($this->getUser());
        $order->setOrderNumber(mt_rand(100000, 999999));
        $order->setQuantity($quantity);
        $order->setTotal($priceTotal);

        $entityManager->persist($order);
        $entityManager->flush();

        $session = $request->getSession();
        $session->set('cart', []);

        return $this->redirectToRoute('app_account');
    }

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
