<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/profil/{name?}', name: 'app_user_show')]
    public function index(
        UserRepository $userRepository,
        Request $request,
        ?string $name
    ): Response
    {
        $loggedUser = $this->getUser();

        if ($name === null && $loggedUser === null) {
            $this->addFlash('warning', 'Une erreur est survenue pour l affichage de ce profil');
            return $this->redirectToRoute('app_home');
        }

        $form = null;
        $user = null;

        if ($name !== null) { // Lorsque je clique sur le compte d'un AUTRE utilisateur
            $user = $userRepository->findOneBy(['name' => $name]);
        }

        if ($loggedUser === $user || $loggedUser && $name === null)  { // Lorsque je clique sur MON COMPTE
            $user = $loggedUser;
            $form = $this->createForm(UserType::class, $user, [
                'isRegistered' => false,
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Oui
            }
        }

        return $this->render('front/user/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
