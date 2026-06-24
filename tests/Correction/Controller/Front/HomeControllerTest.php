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

    public function testTitileExists(): void
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
}
