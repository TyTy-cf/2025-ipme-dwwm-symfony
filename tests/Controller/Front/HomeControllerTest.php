<?php

namespace App\Tests\Controller\Front;

use App\Entity\Game;
use App\Tests\AbstractWebTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;
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

    #[TestWith(['/', 'href', 0])]
    #[TestWith(['/panier', 'href', 1])]
    #[TestWith(['Se connecter', 'txt', 2])]
    #[TestWith(['S\'inscrire', 'txt', 3])]
    public function testHeaderLinkOK(string $content, string $search, int $position): void
    {
        if($search === 'href') {
            $links = $this->crawler->filter('a')
                ->eq($position)
                ->attr('href');
        } else if($search === "txt") {
            $links = $this->crawler->filter('a')
                ->eq($position)
                ->text();
        } else {
            return;
        }

        $this->assertEquals($content, $links);
    }

    #[TestWith(['Les dernières sorties', 1])]
    #[TestWith(['Les meilleures offres', 2])]
    #[TestWith(['Les tendances actuelles', 3])]
    #[TestWith(['Les plus joués', 4])]
    public function testSubtitleOK(string $content, int $position): void
    {
        $subtitle = $this->crawler->filter('h2')
            ->eq($position)
            ->text();

        $this->assertEquals($content, $subtitle);
    }

    #[TestWith(['Far Cry 5', 18])]
    #[TestWith(['Battlefield V', 20])]
    #[TestWith(['Doom Eternal', 22])]
    #[TestWith(['Total War : Warhammer II', 24])]
    #[TestWith(['Call of Duty', 26])]
    #[TestWith(['Borderlands 2', 28])]
    #[TestWith(['ARK : Survival Evolved', 30])]
    #[TestWith(['Super jeu', 32])]
    #[TestWith(['Cyberpunk 2077', 34])]
    public function testGameInSectionOK(string $content, int $position): void
    {
//        $games = static::getContainer()->get(EntityManagerInterface::class)
//            ->getRepository(Game::class)
//            ->findBy(
//                [],
//                ['price' => 'DESC'],
//                9
//            );
//
//        $this->assertNotEmpty($games);
//        $this->assertCount(9, $games);

        $title = $this->crawler->filter('h3')
            ->eq($position)
            ->text();

        $this->assertEquals($content, $title);
    }

    public function testTitleOK(): void
    {
        $titles = $this->crawler->filter('[data-test="best-offer"]')->eq(1)->filter('h3');
        $this->assertCount(18, $titles);
    }

    public function testBestSellerSectionOK(): void
    {
        $section = $this->crawler->filter('[data-test="best-offer"]')->eq(2);
        $this->assertCount(1, $section);
    }

    public function testMostPlayedGamesSectionOK(): void
    {
        $section = $this->crawler->filter('[data-test="best-offer"]')->eq(3);
        $this->assertCount(1, $section);
    }
}
