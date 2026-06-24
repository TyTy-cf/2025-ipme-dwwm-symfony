<?php

namespace App\Tests\Repository;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Tests\AbstractKernelTestCaseTest;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Attributes\TestWith;

class GameRepositoryTest extends AbstractKernelTestCaseTest
{
    private GameRepository $gameRepository;
    public function setUp(): void
    {
        parent::setUp();
        $this->gameRepository = $this->getRepository(GameRepository::class);
    }


    #[TestWith([['Final Fantasy VII', 'Dead by Daylight', 'Monster Hunter : World', 'Hearthstone']])]
    public function testFindByBestSeller(array $gameNames)
    {
        $games = $this->gameRepository->findByBestSeller(count($gameNames));

        self::assertTrue($this->verifyGameArray($games, $gameNames));
    }

    #[TestWith([['Final Fantasy VII', 'Dead by Daylight', 'Monster Hunter : World', 'Starcraft']])]
    public function testGetMostPlayed(array $gameNames)
    {
        $games = $this->gameRepository->getMostPlayedGames(count($gameNames));

        self::assertTrue($this->verifyGameArray($games, $gameNames));
    }

    private function verifyGameArray(array $games, array $gameNames) : bool
    {
        if (count($games) !== count($gameNames))
        {
            return false;
        }

        for ($i = 0; $i < count($games); $i++)
        {
            if ($games[$i]->getName() !== $gameNames[$i])
            {
                return false;
            }
        }

        return true;
    }
}
