<?php

namespace App\Tests\Exercise\Controller\Admin;

use App\Tests\Exercise\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class CountryControllerTest extends AbstractWebTestCase
{

    #[TestWith(["/admin/country"])]
    #[TestWith(["/admin/country/new"])]
    #[TestWith(["/admin/country/edit/1"])]
    public function testLoggedOutRedirects(): void
    {
        $this->client->request("GET", "/admin");
        $this->assertResponseRedirects();
    }

    #[TestWith(["/admin/country"])]
    #[TestWith(["/admin/country/new"])]
    #[TestWith(["/admin/country/edit/1"])]
    public function testPlainUserIsForbidden(string $path): void
    {
        $this->logIn("alivia.cremin@torp.com");
        $this->client->request("GET", $path);
        $this->assertResponseStatusCodeSame(403);
    }

    #[TestWith(["/admin/country"])]
    #[TestWith(["/admin/country/new"])]
    #[TestWith(["/admin/country/edit/1"])]
    public function testAdminHasAccess(string $path): void
    {
        $this->logIn("kevin@drosalys.fr");
        $this->client->request("GET", $path);
        $this->assertResponseIsSuccessful();
    }
}
