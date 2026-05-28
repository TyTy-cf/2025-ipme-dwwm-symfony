<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register', methods: ['POST', 'GET'])]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hashPassword,
    ): Response
    {
        $user = new User();
        // UserType::class => App\Form\UserType
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request); // Lie la requête HTTP en POST (à la soumission du form) à notre objet form

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new DateTimeImmutable());
            $user->setPassword($hashPassword->hashPassword($user, $user->getPassword()));
            $em->persist($user); // Indiquer que l'instance $user de la classe User, doit être "INSERT" en base de données
            $em->flush(); // Envoyer / confirmer que la requête d'INSERT doit passer en base de données

            $this->addFlash('success', 'Inscription réussie !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/register/index.html.twig', [
            'formRegister' => $form,
        ]);
    }
}
