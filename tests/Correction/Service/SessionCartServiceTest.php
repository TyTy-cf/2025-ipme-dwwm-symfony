<?php

namespace App\Tests\Correction\Service;

use App\Repository\GameRepository;
use App\Service\SessionCartService;
use App\Tests\Correction\AbstractKernelTestCase;

class SessionCartServiceTest extends AbstractKernelTestCase
{

    private SessionCartService $sessionCartService;
    private GameRepository $gameRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sessionCartService = $this->get(SessionCartService::class);
        $this->gameRepository = $this->get(GameRepository::class);
    }

    public function testAddItemToCartSession(): void
    {
        $this->addGameToCart(2);
        $this->addGameToCart(3);

        $this->assertEquals(2, $this->sessionCartService->getCartQty());
    }

    private function addGameToCart(int $id): void {
        $game = $this->gameRepository->find($id);
        $this->sessionCartService->addItemToCart($game);
    }

}
