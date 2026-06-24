<?php

namespace App\Tests\Repository;

use App\Entity\Game;
use App\Tests\AbstractKernelTestCaseTest;

class GameRepositoryTest extends AbstractKernelTestCaseTest
{
    public function testFindByBestSeller(): void
    {
        $this->assertRepositoryMethodCount(Game::class, 'findByBestSeller', 9);
    }

    public function testFindByMostPlayedGames(): void
    {
        $this->assertRepositoryMethodCount(Game::class, 'getMostPlayedGames', 9);
    }
}
