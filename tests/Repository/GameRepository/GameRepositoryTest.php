<?php

namespace App\Tests\Repository\GameRepository;

use App\Repository\GameRepository;
use App\Tests\AbstractKernelTestCaseTest;
use PHPUnit\Framework\Attributes\TestWith;

class GameRepositoryTest extends AbstractKernelTestCaseTest
{
    private GameRepository $gameRepository;

    protected function setUp(): void
    {
        $this->gameRepository = parent::getContainer()->get(GameRepository::class);
    }

    #[TestWith([['Final Fantasy VII'], 1])]
    #[TestWith([['Final Fantasy VII', 'Dead by Daylight'], 2])]
    public function testFindByBestSeller(array $expected, int $limit)
    {
        $bestSellerGames = $this->gameRepository->findByBestSeller($limit);

        $this->assertCount($limit, $bestSellerGames);
        for ($i = 0; $i < count($bestSellerGames); $i++) {
            $this->assertSame($expected[$i], $bestSellerGames[$i]->getName());
        }
    }

    #[TestWith([['Final Fantasy VII'], 1])]
    #[TestWith([['Final Fantasy VII', 'Dead by Daylight'], 2])]
    public function testgetMostPlayedGames(array $expected, int $limit)
    {
        $bestSellerGames = $this->gameRepository->findByBestSeller($limit);

        $this->assertCount($limit, $bestSellerGames);
        for ($i = 0; $i < count($bestSellerGames); $i++) {
            $this->assertSame($expected[$i], $bestSellerGames[$i]->getName());
        }
    }
}
