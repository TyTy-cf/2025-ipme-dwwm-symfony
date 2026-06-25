<?php

namespace App\Tests\Exercices\Controller\Front;

use App\Tests\Exercices\AbstractWebTestCaseTest;
use PHPUnit\Framework\Attributes\DataProvider;

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

    #[DataProvider('provideExpectedHeaderLinksLabel')]
    public function testHeaderLinkOLK(int $position, string $expectedLink): void
    {
        $links = $this->crawler->filter('a')
            ->eq($position)
            ->text();

        $this->assertEquals($expectedLink, $links);
    }

    public function provideExpectedHeaderLinksLabel(): array
    {
        return [
            [2, 'Se connecter'],
            [3, 'S\'inscrire']
        ];
    }

    #[DataProvider('provideSubTitlesWithPositions')]
    public function testSubTitlesPresence(string $expectedSubTitle, int $position): void
    {
        $subTitles = $this->crawler->filter('h2')
            ->eq($position)
            ->text();
        $this->assertEquals($expectedSubTitle, $subTitles);
    }

    public static function provideSubTitlesWithPositions(): array
    {
        return [
            ['Les dernières sorties', 1],
            ['Les meilleures offres', 2],
            ['Les tendances actuelles', 3],
            ['Les plus joués', 4]
        ];
    }

    #[DataProvider('provideGamesNamesInBestOffersCategory')]
    public function testGamesPresenceInBestOffersCategory(string $expectedGameName, int $position): void
    {
        $gameName = $this->crawler->filter('.row')
            ->eq(1)
            ->filter('a')
            ->eq($position)
            ->filter('div')
            ->eq(1)
            ->filter('h3')
            ->eq(0)
            ->text();

        $this->assertEquals($expectedGameName, $gameName);
    }

    public static function provideGamesNamesInBestOffersCategory(): array
    {
        return [
            ['Far Cry 5', 0],
            ['Battlefield V', 1],
            ['Doom Eternal', 2],
            ['Total War : Warhammer II', 3],
            ['Call of Duty', 4],
            ['Borderlands 2', 5],
            ['ARK : Survival Evolved', 6],
            ['Super jeu', 7],
            ['Cyberpunk 2077', 8],
        ];
    }

    #[DataProvider('provideCategoryH3Presence')]
    public function testCategroyH3Presence(string $expectedCategroyLink, int $position): void
    {
        $categroyLinks = $this->crawler->filter('.row')
            ->eq(4)
            ->filter('h3')
            ->eq($position)
            ->text();

        $this->assertEquals($expectedCategroyLink, $categroyLinks);
    }

    public static function provideCategoryH3Presence(): array
    {
        return [
            ['Action', 0],
            ['FPS', 1],
            ['Aventure', 2],
            ['Stratégie', 3],
            ['RPG', 4],
            ['MMO', 5],
            ['MOBA', 6],
            ['Monde ouvert', 7],
            ['Simulation', 8],
        ];
    }
}
