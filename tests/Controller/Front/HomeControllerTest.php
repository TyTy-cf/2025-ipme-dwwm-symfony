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

    #[TestWith(['Les dernières sorties', 2])]
    #[TestWith(['Les meilleures offres', 2])]
    #[TestWith(['Les tendances actuelles', 2])]
    #[TestWith(['Les plus joués', 2])]
    #[TestWith(['Action', 3])]
    #[TestWith(['FPS', 3])]
    #[TestWith(['Aventure', 3])]
    #[TestWith(['Stratégie', 3])]
    #[TestWith(['RPG', 3])]
    #[TestWith(['MMO', 3])]
    #[TestWith(['MOBA', 3])]
    #[TestWith(['Monde ouvert', 3])]
    #[TestWith(['Simulation', 3])]
    public function testSubtitle(string $subtitle, int $titleHNumber): void
    {
        $selector = 'h' . $titleHNumber;
        $subtitles = $this->crawler->filter($selector);

        $found = false;
        foreach ($subtitles as $s)
        {
            if (str_contains($s->textContent, $subtitle))
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
