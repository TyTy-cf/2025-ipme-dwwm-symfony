<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Controller\Front;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Exercise\AbstractWebTestCase;

class UserControllerTest extends AbstractWebTestCase
{
    public function testLoggedOutProfilePage(): void
    {
        $crawler = $this->client->request('GET', '/profil/tyrese.kertzmann');
        $this->assertResponseIsSuccessful();
        $this->assertNull($crawler->filter("form button")->getNode(0));
    }

    public function testLoggedInProfilePage(): void
    {
        $this->logIn("alivia.cremin@torp.com");
        $crawler = $this->client->request('GET', '/profil/zrath');
        $this->assertResponseIsSuccessful();

        $formButton = $crawler->filter('#user_submit');
        $this->assertEquals("Valider mon inscription", $formButton->text());
    }

    public function testOtherUserProfilePage(): void
    {
        $this->logIn("alivia.cremin@torp.com");
        $crawler = $this->client->request('GET', '/profil/tyrese.kertzmann');
        $this->assertResponseIsSuccessful();

        $formButton = $crawler->filter('#user_submit');
        $this->assertNull($formButton);
    }
}
