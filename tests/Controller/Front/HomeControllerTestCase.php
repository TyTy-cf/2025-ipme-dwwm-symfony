<?php

namespace App\Tests\Controller\Front;

use App\Tests\AbstractWebTestCase;
use PHPUnit\Metadata\TestWith;

class HomeControllerTestCase extends AbstractWebTestCase
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

    #[\PHPUnit\Framework\Attributes\TestWith([0, ''])]
    #[\PHPUnit\Framework\Attributes\TestWith([1, '0'])]
    #[\PHPUnit\Framework\Attributes\TestWith([2, 'Se connecter'])]
    public function testHeaderLinks(int $order, string $text): void
    {
        $link = $this->crawler->filter('a')->eq($order)->text();
        $this->assertEquals($text, $link);
    }

    #[\PHPUnit\Framework\Attributes\TestWith([0, 'Recherchez un jeu, éditeur...'])]
    #[\PHPUnit\Framework\Attributes\TestWith([1, 'Les dernières sorties'])]
    #[\PHPUnit\Framework\Attributes\TestWith([2, 'Les meilleures offres'])]
    #[\PHPUnit\Framework\Attributes\TestWith([3, 'Les tendances actuelles'])]
    #[\PHPUnit\Framework\Attributes\TestWith([4, 'Les plus joués'])]
    public function testCategoryTitles(int $order, string $text): void
    {
        $title = $this->crawler->filter('h2')->eq($order)->text();
        $this->assertEquals($text, $title);
    }

    #[\PHPUnit\Framework\Attributes\TestWith(['Les dernières sorties'])]
    #[\PHPUnit\Framework\Attributes\TestWith(['Les meilleures offres'])]
    #[\PHPUnit\Framework\Attributes\TestWith(['Les tendances actuelles'])]
    #[\PHPUnit\Framework\Attributes\TestWith(['Les plus joués'])]
    public function testGamesAmountInCategory(string $id): void
    {
        $games = $this->crawler->filter('[data-test-id="' . $id . '"] a');
        $this->assertEquals(9, $games->count());
    }
}
