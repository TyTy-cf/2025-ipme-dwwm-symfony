<?php

namespace App\Tests\Correction\Controller\Api\Country;

use App\Tests\Correction\AbstractApiTestCaseTest;

class GetControllerTest extends AbstractApiTestCaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$COUNTRY;
    }

    public function testGetCountryOk(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $this->client->request('GET', self::$COUNTRY . '/' . $this->country->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'code' => 'TE',
            'name' => 'Test Country',
        ]);
    }
}
