<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Service;

use App\DTO\CartDTO;
use App\DTO\GameDTO;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Service\SessionCartService;
use App\Tests\Exercise\AbstractKernelTestCase;
use App\Tests\Exercise\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class SessionCartServiceTest extends AbstractKernelTestCase
{
    private SessionCartService $service;
    private GameRepository $gameRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockSession();
        $this->service = static::getContainer()->get(SessionCartService::class);
        $this->gameRepository = static::getContainer()->get(GameRepository::class);
    }

    /** @throws ExceptionInterface */
    public function testGetCart(): void
    {
        $cart = $this->service->getCart();
        $inCart = count($cart->getGamesDTO());

        $this->assertInstanceOf(CartDTO::class, $cart);
        $this->assertEquals(0, $inCart);
    }

    /** @throws ExceptionInterface */
    public function testAddItemToCart(): void
    {
        $game1 = $this->gameRepository->findOneBy(['id' => 1]);
        $game2 = $this->gameRepository->findOneBy(['id' => 2]);
        $this->service->addItemToCart($game1);
        $this->service->addItemToCart($game2);
        $cart = $this->service->getCart();
        
        $this->assertCount(2, $cart->getGamesDTO());
        $this->assertGreaterThan(0, $cart->getTotalPrice());
        $this->assertInstanceOf(GameDTO::class, $cart->getGamesDTO()[0]);
    }


    /** @throws ExceptionInterface */
    public function testClearCart(): void
    {
        $game = $this->gameRepository->findOneBy(['id' => 1]);
        $this->service->addItemToCart($game);
        $cartGame = $this->service->getCart()->getGamesDTO()[0];

        $this->assertInstanceOf(GameDTO::class, $cartGame);

        $this->service->clearCart();
        $inCart = $this->service->getCartQty();

        $this->assertEquals(0, $inCart);
    }
}


