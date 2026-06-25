<?php

namespace App\Tests\API\Controller;

use App\Tests\AbstractApiTestCaseTest;

class UserMeControllerTest extends AbstractApiTestCaseTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$USER_ME;
    }
    public function testUserMeEndpoint(): void
    {
        $data = $this->testAuthenticatedEndpoint('zprosacco@hotmail.com', '12345', self::$GET);

        $this->assertSame("zprosacco@hotmail.com", $data["email"]);
        $this->assertSame("morgan93", $data["name"]);
        $this->assertSame("pvandervort", $data["nickname"]);
        $this->assertSame(null, $data["profileImage"]);
        $this->assertSame(37, $data["wallet"]);
    }
}
