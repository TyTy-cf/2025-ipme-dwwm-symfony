<?php

namespace App\Tests\Correction\Controller\Front;

use App\Tests\Correction\AbstractWebTestCaseTest;

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

    public function testHeaderLinkOLK(): void
    {
        $links = $this->crawler->filter('a')
            ->eq(2)
            ->text();

        $this->assertEquals('Se connecter', $links);
    }
}
