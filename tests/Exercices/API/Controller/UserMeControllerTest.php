<?php

namespace App\Tests\Exercices\API\Controller;

use App\Tests\Exercices\AbstractApiTestCaseTest;

class UserMeControllerTest extends AbstractApiTestCaseTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$USER_ME;
    }
    public function testUserMeEndpoint(): void
    {
        $response = $this->testAuthenticatedEndpoint('zprosacco@hotmail.com', '12345', self::$GET);

        $data = json_decode($response->getContent(), true);

        $this->assertSame("zprosacco@hotmail.com", $data["email"]);
        $this->assertSame("morgan93", $data["name"]);
        $this->assertSame("pvandervort", $data["nickname"]);
        $this->assertSame(null, $data["profileImage"]);
        $this->assertSame(37, $data["wallet"]);
    }
}
