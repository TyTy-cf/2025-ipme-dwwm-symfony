<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Repository\GameRepository;
use App\Service\SessionCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class AjaxCartController extends AbstractController
{

    /**
     * @throws ExceptionInterface
     */
    #[Route('/ajax/add-game-to-cart/{id}', name: 'app_add_game_cart')]
    public function addGameCart(
        GameRepository     $gameRepository,
        SessionCartService $sessionCartService,
        string             $id,
    ): JsonResponse
    {
        if (null === $game = $gameRepository->findOneBy(['id' => $id])) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }
        $sessionCartService->addItemToCart($game);
        return new JsonResponse([
            'qty' => $sessionCartService->getCartQty(),
        ], Response::HTTP_OK);
    }

}
