<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategoriesRepository;
use App\Trait\CartTrait;

#[Route('/users')]
class UsersController extends AbstractController
{
    use CartTrait;

    #[Route('/account', name: 'app_account', methods: ['GET'])]
    public function account(Request $request, CategoriesRepository $categoriesRepository): Response
    {
        $user = $this->getUser();
        $cartQuantity = $this->cartQuantity($request);
        $categories = $categoriesRepository->findAll();

        return $this->render('users/account.html.twig', [
            'user' => $user,
            'cartQuantity' => $cartQuantity,
            'categories' => $categories,
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET'])]
    public function contact(Request $request, CategoriesRepository $categoriesRepository): Response
    {
        $cartQuantity = $this->cartQuantity($request);
        $categories = $categoriesRepository->findAll();

        return $this->render('users/contact.html.twig', [
            'cartQuantity' => $cartQuantity,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
