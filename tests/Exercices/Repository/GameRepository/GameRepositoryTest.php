<?php

namespace App\Tests\Exercices\Repository\GameRepository;

use App\Repository\GameRepository;
use App\Tests\Exercices\AbstractKernelTestCaseTest;
use PHPUnit\Framework\Attributes\TestWith;

class GameRepositoryTest extends AbstractKernelTestCaseTest
{
    private GameRepository $gameRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gameRepository = parent::getClass(GameRepository::class);
    }

    #[TestWith([['Final Fantasy VII'], 1])]
    #[TestWith([['Final Fantasy VII', 'Dead by Daylight'], 2])]
    public function testFindByBestSeller(array $expected, int $limit)
    {
        $bestSellerGames = $this->gameRepository->findByBestSeller($limit);

        $this->assertCount($limit, $bestSellerGames);
        $this->loopGame($expected, $bestSellerGames);

    }

    #[TestWith([['Final Fantasy VII'], 1])]
    #[TestWith([['Final Fantasy VII', 'Dead by Daylight'], 2])]
    public function testgetMostPlayedGames(array $expected, int $limit)
    {
        $bestSellerGames = $this->gameRepository->findByBestSeller($limit);

        $this->assertCount($limit, $bestSellerGames);
        $this->loopGame($expected, $bestSellerGames);
    }

    private function loopGame(array $expected, array $bestSellerGames)
    {
        foreach($bestSellerGames as $key => $game) {
            $this->assertSame($expected[$key], $game->getName());
        }
    }
}
