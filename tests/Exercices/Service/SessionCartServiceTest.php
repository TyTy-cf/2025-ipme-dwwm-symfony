<?php

namespace App\Tests\Exercices\Service;

use App\DTO\CartDTO;
use App\DTO\GameDTO;
use App\Repository\GameRepository;
use App\Service\SessionCartService;
use App\Tests\Exercices\AbstractKernelTestCaseTest;

class SessionCartServiceTest extends AbstractKernelTestCaseTest
{
    private GameRepository $gameRepository;
    private SessionCartService $sessionCartService;
    const GAME_IDS = [1, 2];

    public function setUp(): void
    {
        parent::setUp();
        $this->sessionCartService = parent::getClass(SessionCartService::class);
        $this->gameRepository = parent::getClass(GameRepository::class);
        parent::setUpRequestStack();
    }

    public function testAddItemToCart(): void
    {
        $this->constructCart(self::GAME_IDS);
        $cartQty = $this->sessionCartService->getCartQty();

        $this->assertEquals(count(self::GAME_IDS), $cartQty);
    }

    public function testGetCart(): void
    {
        $this->constructCart(self::GAME_IDS);
        $cart = $this->sessionCartService->getCart();

        $this->isInstanceOf(CartDTO::class);
        $this->assertCount(count(self::GAME_IDS), $cart->getGamesDTO());

        foreach ($cart->getGamesDTO() as $game) {
            $this->isInstanceOf(GameDTO::class);
        }
    }

    public function testClearCart(): void
    {
        $this->constructCart(self::GAME_IDS);

        $this->sessionCartService->clearCart();

        $cart = $this->sessionCartService->getCart();
        $this->assertEmpty($cart->getGamesDTO());
    }

    private function constructCart(array $gamesId): void
    {
        foreach ($gamesId as $gameId) {
            $game = $this->gameRepository->findOneBy(['id' => $gameId]);
            $this->sessionCartService->addItemToCart($game);
        }
    }
}
