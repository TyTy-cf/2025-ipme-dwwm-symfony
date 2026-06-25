<?php

namespace App\Tests\API\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\AbstractApiTestCaseTest;

class LoginCheckTest extends AbstractApiTestCaseTest
{
    private const USER_EMAIL = 'zprosacco@hotmail.com';
    private const USER_PASSWORD = '12345';

    public function testGetAuthTokenOk(): void
    {
        $this->getAuthToken(self::USER_EMAIL, self::USER_PASSWORD);
        $this->assertResponseIsSuccessful();
    }

    public function testGetAuthTokenFail(): void
    {
        $token = $this->getAuthToken(self::USER_EMAIL, '1234566');
        $this->assertNull($token);
    }
}
