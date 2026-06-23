<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Tests\AbstractKernelTestCase;

class GameRepositoryTest extends AbstractKernelTestCase
{
    private GameRepository $gameRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gameRepository = $this->get(GameRepository::class);
    }

    public function testBestSellers(): void
    {
        /** @var Game[] $bestSellers */
        $bestSellers = $this->gameRepository->findByBestSeller(9);

        $this->assertCount(9, $bestSellers);
        $this->assertEquals('Final Fantasy VII', $bestSellers[0]->getName());
    }

    public function testMostPlayed(): void
    {
        $mostPlayed = $this->gameRepository->getMostPlayedGames(9);

        $this->assertCount(9, $mostPlayed);
        $this->assertEquals('Final Fantasy VII', $mostPlayed[0]->getName());
    }
}
