<?php

namespace App\Controller\Front;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

    #[Route(name: 'app_home')]
    public function index(
        GameRepository         $gameRepository,
        ReviewRepository       $reviewRepository,
        CategoryRepository     $categoryRepository,
    ): Response
    {
        $lastGames = $gameRepository->findBy(
            [],
            ['publishedAt' => 'DESC'],
            9
        );
        $expensiveGames = $gameRepository->findBy(
            [],
            ['price' => 'DESC'],
            9
        );
        $bestSellers = $gameRepository->findByBestSeller(9);
        $mostPlayedGames = $gameRepository->getMostPlayedGames(9);

        $lastReviews = $reviewRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            4
        );

        $categories = $categoryRepository->findByMostPlayed(9);

        return $this->render('front/home/index.html.twig', [
            'lastGames' => $lastGames,
            'expensiveGames' => $expensiveGames,
            'bestSellers' => $bestSellers,
            'mostPlayedGames' => $mostPlayedGames,
            'lastReviews' => $lastReviews,
            'categories' => $categories,
            'reviews' => $reviewRepository->findBy([], ['createdAt' => 'DESC'], 4),
        ]);
    }

}
