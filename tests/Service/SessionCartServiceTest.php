<?php

namespace App\Tests\Service;

use App\Repository\GameRepository;
use App\Service\SessionCartService;
use App\Tests\AbstractKernelTestCaseTest;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class SessionCartServiceTest extends AbstractKernelTestCaseTest
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

    /**
     * @throws ExceptionInterface
     */
    public function testAddItemToCartSession(): void
    {
        $this->addGameToCart(2);
        $this->addGameToCart(3);

        $this->assertEquals(2, $this->sessionCartService->getCartQty());
    }

    /**
     * @throws ExceptionInterface
     */
    private function addGameToCart(int $id): void {
        $game = $this->gameRepository->find($id);
        $this->sessionCartService->addItemToCart($game);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testCartQuantity(): void
    {
        $this->addGameToCart(5);
        $quantity = $this->sessionCartService->getCartQty();

        $this->assertEquals(1, $quantity);
    }

    /**
     * @throws ExceptionInterface
     */
    public function testClearCart(): void
    {
        $this->addGameToCart(5);
        $quantityAfterAdd = $this->sessionCartService->getCartQty();
        $this->assertEquals(1, $quantityAfterAdd);

        $this->sessionCartService->clearCart();
        $quantity = $this->sessionCartService->getCartQty();

        $this->assertEquals(0, $quantity);
    }
}
