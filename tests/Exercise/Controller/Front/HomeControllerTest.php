<?php

namespace App\Tests\Exercise\Controller\Front;

use App\Tests\Exercise\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class HomeControllerTest extends AbstractWebTestCase
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

    #[TestWith([0, ''])]
    #[TestWith([1, '0'])]
    #[TestWith([2, 'Se connecter'])]
    public function testHeaderLinks(int $order, string $text): void
    {
        $link = $this->crawler->filter('a')->eq($order)->text();
        $this->assertEquals($text, $link);
    }

    #[TestWith([0, 'Recherchez un jeu, éditeur...'])]
    #[TestWith([1, 'Les dernières sorties'])]
    #[TestWith([2, 'Les meilleures offres'])]
    #[TestWith([3, 'Les tendances actuelles'])]
    #[TestWith([4, 'Les plus joués'])]
    public function testCategoryTitles(int $order, string $text): void
    {
        $title = $this->crawler->filter('h2')->eq($order)->text();
        $this->assertEquals($text, $title);
    }

    #[TestWith(['Les dernières sorties'])]
    #[TestWith(['Les meilleures offres'])]
    #[TestWith(['Les tendances actuelles'])]
    #[TestWith(['Les plus joués'])]
    public function testGamesAmountInCategory(string $id): void
    {
        $games = $this->crawler->filter('[data-test-id="' . $id . '"] a');
        $this->assertEquals(9, $games->count());
    }
}
