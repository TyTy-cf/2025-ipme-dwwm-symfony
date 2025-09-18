<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            $review->setUser($this->getUser());
            $review->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_game', [
                'slug' => $game->getSlug(),
            ]);
        }

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'form' => $form,
            'reviews' => $reviewRepository->findBy(['game' => $game], ['createdAt' => 'DESC'], 4),
            'categories' => $game->getCategories(),
        ]);
    }

    #[Route('/categorie/{slug}', name: 'app_game_category')]
    public function gameCategory(
        string             $slug,
        Request            $request,
        PaginatorInterface $paginator,
        CategoryRepository $categoryRepository
    ): Response
    {
        $category = $categoryRepository->findFullBySlug($slug);

        if ($category === null) {
            $this->addFlash('danger', 'Cette catégorie n\'existe pas !');
            return $this->redirectToRoute('app_home');
        }

        $perPage = 12;

        $pagination = $paginator->paginate(
            $categoryRepository->findFullBySlug($slug)->getGames(),
            $request->query->getInt('page', 1),
            $perPage
        );

        return $this->render('game/games_by_category.html.twig', [
            'category' => $category,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/handle-wishlist/{id}', name: 'app_handle_wishlist', methods: ['POST'])]
    public function handleWishlist(
        string $id,
        UserRepository $userRepository,
        GameRepository $gameRepository,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $game = $gameRepository->find($id);
        $user = $this->getUser();
        $action = '/se-connecter'; // nothing
        $user = $userRepository->findOneBy(['id' => 42]); // Car j'ai pas de security !

        /** @var User $user */
        if ($user) {
            if ($user->getWantedGames()->contains($game)) {
                $user->removeWantedGame($game);
                $action = 100; // remove
            } else {
                $user->addWantedGame($game);
                $action = 200; // add
            }
            $em->flush();
        }

        return new JsonResponse($action);
    }

}
