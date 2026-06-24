<?php

namespace App\Tests\Service;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Service\SessionCartService;
use App\Tests\AbstractKernelTestCaseTest;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SessionCartServiceTest extends AbstractKernelTestCaseTest
{
    private GameRepository $gameRepository;
    private SessionCartService $cartService;
    public function setUp(): void
    {
        parent::setUp();
        $this->gameRepository = $this->getRepository(GameRepository::class);
        $this->cartService = $this->getService(SessionCartService::class);

        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);

        $requestStack = static::getContainer()->get(RequestStack::class);
        $requestStack->push($request);
    }


    #[TestWith(['Final Fantasy VII'])]
    public function testAddToCart(string $gameName)
    {
        $game = $this->gameRepository->findOneBy(['name' => $gameName]);

        if ($game !== null)
        {
            $this->cartService->addItemToCart($game);
        }

        $this->assertEquals($this->cartService->getCart()->getGamesDTO()[0]->getName(), $gameName);
    }

    #[TestWith([[1, 2]])]
    #[TestWith([[1, 2, 3]])]
    public function testCartQty(array $gameIds)
    {
        foreach ($gameIds as $gameId)
        {
            $game = $this->gameRepository->findOneBy(['id' => $gameId]);
            if ($game !== null)
            {
                $this->cartService->addItemToCart($game);
            }
        }

        $this->assertCount(count($gameIds), $this->cartService->getCart()->getGamesDTO());
    }

    #[TestWith([[1, 2, 3]])]
    public function testClearCart(array $gameIds)
    {
        foreach ($gameIds as $gameId)
        {
            $game = $this->gameRepository->findOneBy(['id' => $gameId]);
            if ($game !== null)
            {
                $this->cartService->addItemToCart($game);
            }
        }
        $this->cartService->clearCart();

        $this->assertCount(0, $this->cartService->getCart()->getGamesDTO());
    }

}
