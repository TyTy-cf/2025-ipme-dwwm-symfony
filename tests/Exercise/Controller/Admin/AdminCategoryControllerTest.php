<?php

namespace App\Tests\Exercise\Controller\Admin;

use App\Tests\Exercise\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AdminCategoryControllerTest extends AbstractWebTestCase
{

    #[TestWith(["/admin/category/nouvelle"])]
    public function testLoggedOutRedirects(): void
    {
        $this->client->request("GET", "/admin");
        $this->assertResponseRedirects();
    }

    #[TestWith(["/admin/category/nouvelle"])]
    public function testPlainUserIsForbidden(string $path): void
    {
        $this->logIn("alivia.cremin@torp.com");
        $this->client->request("GET", $path);
        $this->assertResponseStatusCodeSame(403);
    }

    #[TestWith(["/admin/category/nouvelle"])]
    public function testAdminHasAccess(string $path): void
    {
        $this->logIn("kevin@drosalys.fr");
        $this->client->request("GET", $path);
        $this->assertResponseIsSuccessful();
    }
}
