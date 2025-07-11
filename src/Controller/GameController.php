<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{

    #[Route('/jeux/{slug}', name: 'app_show_game')]
    public function show(
        string                 $slug,
        GameRepository         $gameRepository,
        ReviewRepository       $reviewRepository,
        Request                $request,
        EntityManagerInterface $entityManager,
        UserRepository         $userRepository,
    ): Response
    {
        $game = $gameRepository->findOneBy(['slug' => $slug]);
        if ($game === null) {
            // Utilisable seulement dans un controller !
            $this->addFlash('danger', 'Ce jeu n\'existe pas !');
            return $this->redirectToRoute('app_home');
        }

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setGame($game);
            $review->setUser($userRepository->findOneBy(['id' => 42]));
            $review->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_game', [
                'slug' => $game->getSlug()
            ]);
        }

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'form' => $form,
            'reviews' => $reviewRepository->findBy(['game' => $game], ['createdAt' => 'DESC'], 4),
        ]);
    }

    #[Route('/categorie/{slug}', name: 'app_game_category')]
    public function gameCategory(
        string             $slug,
        CategoryRepository $categoryRepository
    ): Response
    {
        $category = $categoryRepository->findFullBySlug($slug);
        if ($category === null) {
            $this->addFlash('danger', 'Cette catégorie n\'existe pas !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('game/games_by_category.html.twig', [
            'category' => $category,
        ]);
    }

}
