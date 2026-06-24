<?php

namespace App\Tests\Controller\Front;

use App\Repository\UserRepository;
use App\Tests\AbstractWebTestCaseTest;
use PHPUnit\Framework\Attributes\TestWith;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

class UserControllerTest extends AbstractWebTestCaseTest
{

    protected function setUp(): void
    {
        $this->defaultUrl = self::$PROFILE;
        parent::setUp();
    }

    public function testOtherProfileNotLoggedIn()
    {
        $other = static::getContainer()->get(UserRepository::class)->findOneBy(['id' => 2]);
        $this->defaultUrl = $this->defaultUrl . "/" . $other->getName();
        $this->crawler = $this->client->request('GET', $this->defaultUrl);

        self::assertResponseIsSuccessful();
    }

    public function testMyProfileLoggedIn()
    {
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['id' => 1]);
        $this->client->loginUser($user);
        $this->crawler = $this->client->request('GET', $this->defaultUrl);

        $form = $this->crawler->selectButton('Valider mon inscription')->form();
        assertNotNull($form);
    }

//    public function testOtherProfileLoggedIn()
//    {
//        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['id' => 1]);
//        $other = static::getContainer()->get(UserRepository::class)->findOneBy(['id' => 2]);
//        $this->client->loginUser($user);
//        $this->defaultUrl = $this->defaultUrl . "/" . $other->getName();
//        dump($this->defaultUrl);
//        dump($other->getName());
//        dump($user->getName());
//        $this->crawler = $this->client->request('GET', $this->defaultUrl);
//
//        self::assertSelectorExists('form[name="user"]');
//    }
}
