<?php

namespace App\Tests\Correction\Service;

use App\DTO\CartDTO;
use App\DTO\GameDTO;
use App\Repository\GameRepository;
use App\Service\SessionCartService;
use App\Tests\Correction\AbstractKernelTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class SessionCartServiceTest extends AbstractKernelTestCase
{

    private SessionCartService $sessionCartService;
    private GameRepository $gameRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRequestStack();
        $this->sessionCartService = $this->get(SessionCartService::class);
        $this->gameRepository = $this->get(GameRepository::class);
    }

    #[TestWith([2], 'Test OK add game id 2')]
    #[TestWith([3], 'Test OK add game id 3')]
    #[TestWith([5], 'Test OK add game id 4')]
    #[TestWith([642], 'Test KO add game id 642')]
    public function testAddItemToCart(int $id): void
    {
        if ($id === 642) {
            $this->assertFalse($this->addGameToCart($id));
        } else {
            $this->assertTrue($this->addGameToCart($id));
        }
    }

    #[TestWith([[2, 3]], 'Test OK add 2 games')]
    #[TestWith([[4, 7, 9, 15]], 'Test OK add 4 games')]
    #[TestWith([[4, 7, 9, 15, 642]], 'Test KO add 5 games')]
    public function testGetCartQty(array $ids): void
    {
        $nb = $this->loopAddGame($ids);
        $this->assertEquals($nb, $this->sessionCartService->getCartQty());
    }

    #[TestWith([[2, 3]], 'Test instance with 2 games')]
    #[TestWith([[4, 7, 9, 15]], 'Test instance with 4 games')]
    public function testGetCart(array $ids): void
    {
        $this->loopAddGame($ids);

        $cart = $this->sessionCartService->getCart();
        $this->assertInstanceOf(CartDTO::class, $cart);

        foreach ($cart->getGamesDTO() as $item) {
            $this->assertInstanceOf(GameDTO::class, $item);
        }
    }

    #[TestWith([[2, 3]], 'Test clear 2 games')]
    #[TestWith([[4, 7, 9, 15]], 'Test clear 4 games')]
    public function testClearCart(array $ids): void
    {
        $this->loopAddGame($ids);

        $this->sessionCartService->clearCart();
        $this->assertCount(0, $this->sessionCartService->getCart()->getGamesDTO());
    }

    private function addGameToCart(int $id): bool {
        if (null === $game = $this->gameRepository->find($id)) {
            return false;
        }
        return $this->sessionCartService->addItemToCart($game);
    }

    private function loopAddGame(array $ids): int {
        $nb = 0;
        foreach ($ids as $id) {
            if ($this->addGameToCart($id)) {
                $nb++;
            }
        }
        return $nb;
    }

}
