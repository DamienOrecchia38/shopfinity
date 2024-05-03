<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ChangePasswordType;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Trait\CartTrait;

class SecurityController extends AbstractController
{
    use CartTrait;

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/{id}/change-password', name: 'app_users_update_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, Users $user, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, OrdersRepository $ordersRepository): Response
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            $entityManager->flush();

            return $this->redirectToRoute('app_account');
        }

        $user = $this->getUser();
        $cartQuantity = $this->cartQuantity($request);
        $allOrders = $ordersRepository->findAll();
        $userOrders = [];
    
        foreach ($allOrders as $order) {
            if ($order->getUsers()->getId() === $user->getId()) {
                $userOrders[] = $order;
            }
        }

        return $this->render('security/update_password.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'userOrders' => $userOrders,
            'cartQuantity' => $cartQuantity,
        ]);
    }
}
