<?php

namespace App\Tests\Exercise\Controller\Admin;

use App\Tests\Exercise\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class GameControllerTest extends AbstractWebTestCase
{

    #[TestWith(["/admin/game"])]
    #[TestWith(["/admin/game/new"])]
    #[TestWith(["/admin/game/edit/1"])]
    public function testLoggedOutRedirects(): void
    {
        $this->client->request("GET", "/admin");
        $this->assertResponseRedirects();
    }

    #[TestWith(["/admin/game"])]
    #[TestWith(["/admin/game/new"])]
    #[TestWith(["/admin/game/edit/1"])]
    public function testPlainUserIsForbidden(string $path): void
    {
        $this->logIn("alivia.cremin@torp.com");
        $this->client->request("GET", $path);
        $this->assertResponseStatusCodeSame(403);
    }

    #[TestWith(["/admin/game"])]
    #[TestWith(["/admin/game/new"])]
    #[TestWith(["/admin/game/edit/1"])]
    public function testAdminHasAccess(string $path): void
    {
        $this->logIn("kevin@drosalys.fr");
        $this->client->request("GET", $path);
        $this->assertResponseIsSuccessful();
    }
}
