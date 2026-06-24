<?php

namespace App\Tests\Correction\Controller\Front;

use App\Tests\Correction\AbstractWebTestCaseTest;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Component\DomCrawler\Crawler;

class UserControllerTest extends AbstractWebTestCaseTest
{

    static string $FORM_SELECTOR = 'form[name="user"]';

    protected function setUp(): void
    {
        $this->defaultUrl = self::$PROFILE;
        parent::setUp();
    }

    #[TestWith(['fschmeler@gmail.com'], 'Test profile OK as not self, without form')]
    #[TestWith([null], 'Test profile OK as anonymous, without form')]
    public function testProfileNotSelf(?string $email): void
    {
        $this->login($email);
        $this->crawler = $this->client->request('GET', $this->defaultUrl);
        $this->assertSelectorNotExists(self::$FORM_SELECTOR);
    }

}
