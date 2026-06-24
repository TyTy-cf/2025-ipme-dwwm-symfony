<?php

namespace App\Tests\Correction\Controller\Front;

use App\Tests\Correction\AbstractWebTestCaseTest;
use PHPUnit\Framework\Attributes\TestWith;

class HomeControllerTest extends AbstractWebTestCaseTest
{

    protected function setUp(): void
    {
        $this->defaultUrl = self::$HOME;
        parent::setUp();
    }

    public function testTitleExists(): void
    {
        $this->assertSelectorTextContains('h1', 'SteamIsh V3');
    }

    #[TestWith([0, '/'], 'Ensure link to home')]
    #[TestWith([1, '/panier'], 'Ensure link to panier')]
    #[TestWith([2, '/login'], 'Ensure link to login')]
    #[TestWith([3, '/inscription'], 'Ensure link to inscription')]
    public function testHeaderLinks(int $order, string $href): void
    {
        $link = $this->crawler->filter('a')->eq($order)->attr('href');
        $this->assertEquals($href, $link);
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
            if ($titleHNumber === 3) {
                /** @vae DOMElement $s */
                dump($s->nodeValue);
                dump($s->textContent);
                dump($subtitle);
            }
            if (str_contains($s->textContent, $subtitle))
            {
                $found = true;
            }
        }

        $this->assertTrue($found);
    }
}
