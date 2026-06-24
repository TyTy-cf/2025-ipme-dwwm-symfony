<?php

namespace App\Tests\Controller\Front;

use App\Tests\AbstractWebTestCaseTest;
use PHPUnit\Framework\Attributes\TestWith;

class HomeControllerTest extends AbstractWebTestCaseTest
{

    protected function setUp(): void
    {
        $this->defaultUrl = self::$HOME;
        parent::setUp();
    }

    public function testAccessOK(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'SteamIsh V3');
    }

    #[TestWith([2])]
    public function testHeaderLinkOK(int $position): void
    {
        $links = $this->crawler->filter('a')
            ->eq($position)
            ->text();

        $this->assertEquals('Se connecter', $links);
    }

    #[TestWith(['Les dernières sorties'])]
    #[TestWith(['Les meilleures offres'])]
    #[TestWith(['Les tendances actuelles'])]
    #[TestWith(['Les plus joués'])]
    public function testSubtitle(string $subtitle): void
    {
        $subtitles = $this->crawler->filter('h2');

        $found = false;
        foreach ($subtitles as $s)
        {
            if ($subtitle === $s->textContent)
            {
                $found = true;
            }
        }

        $this->assertTrue($found);
    }

    #[TestWith(['Les meilleures offres',
        [
            'Far Cry 5',
            'Battlefield V',
            'Doom Eternal',
            'Total War : Warhammer II',
            'Call of Duty',
            'Borderlands 2',
            'ARK : Survival Evolved',
            'Super jeu',
            'Cyberpunk 2077'
        ]
    ])]
    public function testGameCollection(string $category, array $games): void
    {
        $subtitles = $this->crawler->filter('h2');
        $count = 0;
        foreach ($subtitles as $s)
        {
            if ($category === $s->textContent)
            {
                $div = $s->nextElementSibling;
                if ($div !== null)
                {
                    foreach ($games as $game)
                    {
                        if ($this->isFoundInChildren($game, $div))
                        {
                            $count++;
                        }
                    }
                }
            }
        }
        self::assertTrue(count($games) === $count);
    }


    private function isFoundInChildren(string $needle, $parent): bool
    {
        foreach ($parent->childNodes as $child)
        {
            if (str_contains($child->textContent, $needle))
            {
                return true;
            }
            else
            {
                if ($this->isFoundInChildren($needle, $child))
                {
                    return true;
                }
            }
        }
        return false;
    }
}
