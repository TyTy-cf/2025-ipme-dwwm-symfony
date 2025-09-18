<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserOwnGameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/profil/{name?}')]
    public function index(
        UserRepository        $userRepository,
        UserOwnGameRepository $userOwnGameRepository,
        Request               $request,
        ?string               $name
    ): Response
    {
        $user = $this->getUser();
        $totalGameTime = $userOwnGameRepository->getTotalGameTime($name);

        if ($name === null && $user === null) {
            $this->addFlash('warning', 'Une erreur est survenue pour l affichage de ce profil');
            return $this->redirectToRoute('app_home');
        }

        $form = null;

        if ($name !== null && $user === null) { // Lorsque je clique sur le compte d'un AUTRE utilisateur
            $user = $userRepository->findOneBy(['name' => $name]);
        } else { // Lorsque je clique sur MON COMPTE
            $form = $this->createForm(UserType::class, $user, [
                'isRegistered' => false,
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Oui
            }
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form,
            'totalGameTime' => $totalGameTime,
        ]);
    }
}
