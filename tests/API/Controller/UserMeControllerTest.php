<?php

namespace App\Tests\API\Controller;

use App\Tests\AbstractApiTestCaseTest;

class UserMeControllerTest extends AbstractApiTestCaseTest
{
    private const USER_EMAIL = 'zprosacco@hotmail.com';
    private const USER_PASSWORD = '12345';

    public function testGetAuthToken(): void
    {
        $this->getAuthToken(self::USER_EMAIL, self::USER_PASSWORD);
        $this->assertResponseIsSuccessful();
    }

    public function testUserMeEndpoint(): void
    {
        $data = $this->testAuthenticatedEndpoint(self::USER_EMAIL, self::USER_PASSWORD, self::$GET, self::$USER_ME);

        $this->assertSame("zprosacco@hotmail.com", $data["email"]);
        $this->assertSame("morgan93", $data["name"]);
        $this->assertSame("pvandervort", $data["nickname"]);
        $this->assertSame(null, $data["profileImage"]);
        $this->assertSame(37, $data["wallet"]);
    }
}
