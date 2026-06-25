<?php

namespace App\Tests\API\Controller;

use App\Tests\AbstractApiTestCaseTest;

class CountryApiTest extends AbstractApiTestCaseTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$COUNTRIES_URL;
    }

    public function testPostCountryWithAdminUser()
    {

        $body = [
            "code" => "fk",
            "name" => "fake country",
            "nationality" =>  "fake country",
        ];

        $response = $this->testAuthenticatedEndpoint('kevin@drosalys.fr', '12345', self::$POST, $body);
        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('nationality', $data);
        $this->assertArrayHasKey('urlFlag', $data);
        $this->assertArrayHasKey('slug', $data);
    }

    public function testPostCountryWithNoAdminUser()
    {

        $body = [
            "code" => "fk",
            "name" => "fake country",
            "nationality" =>  "fake country",
        ];

        $response = $this->testAuthenticatedEndpoint('zprosacco@hotmail.com', '12345', self::$POST, $body);
        $this->assertSame(403, $response->getStatusCode());
    }
}
