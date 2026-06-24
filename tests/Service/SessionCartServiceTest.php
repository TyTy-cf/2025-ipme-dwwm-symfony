<?php

namespace App\Tests\Service;

use App\DTO\CartDTO;
use App\DTO\GameDTO;
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
    public function testGetCartOk(): void
    {
        $this->addGameToCart(6);
        $this->addGameToCart(7);

        $cart = $this->sessionCartService->getCart();
        $this->assertInstanceOf(CartDTO::class, $cart);
        $this->assertCount(2, $cart->getGamesDTO());
        foreach ($cart->getGamesDTO() as $game) {
            $this->assertInstanceOf(GameDTO::class, $game);
        }
    }

    /**
     * @throws ExceptionInterface
     */
    public function testClearCart(): void
    {
        $this->addGameToCart(5);

        $this->sessionCartService->clearCart();
        $quantity = $this->sessionCartService->getCartQty();

        $this->assertEquals(0, $quantity);
    }
}
