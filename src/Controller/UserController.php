<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Game;
use App\Entity\UserOwnGame;
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
        UserRepository $userRepository,
        Request $request,
        UserOwnGameRepository $userOwnGameRepository,
        ?string $name
    ): Response
    {
        $user = $this->getUser();
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
       $myGames = $userOwnGameRepository->findBy(['user' => $user]);

        dump($myGames);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form,
            'myGames' => $myGames,


        ]);
    }

    private function findOneBy(array $array)
    {
    }
}
