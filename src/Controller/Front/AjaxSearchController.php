<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AjaxSearchController extends AbstractController
{

    #[Route('/ajax-search/{search}')]
    public function index(
        string              $search,
        Request             $request,
        GameRepository      $gameRepository,
        PublisherRepository $publisherRepository,
        CategoryRepository  $categoryRepository,
    ): JsonResponse
    {
        $results = [];
//        $searchedValue = json_decode($request->getContent(), true)['search'];
        $searchedValue = $search;

        // K = type of results
        // V = [name, slug], [name, slug], ...
        $results['game'] = $gameRepository->findBySearch($searchedValue);
        $results['category'] = $categoryRepository->findBySearch($searchedValue);
        $results['publisher'] = $publisherRepository->findBySearch($searchedValue);

        $html = $this->renderView('front/partials/_search_results.html.twig', [
            'results' => $results
        ]);

        return new JsonResponse([
            'html' => $html,
        ], Response::HTTP_OK);
    }

}
