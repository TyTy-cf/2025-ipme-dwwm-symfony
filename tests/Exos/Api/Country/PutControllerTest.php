<?php

namespace App\Tests\Correction\Controller\Api\Country;

use App\Repository\CountryRepository;
use App\Tests\Exos\AbstractApiTestCaseTest;

class PutControllerTest extends AbstractApiTestCaseTest
{
    protected function setUp(): void {

        parent::setUp();
        $this->defaultUrl = self::$COUNTRY;
    }

    public function testPutCountryOk(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $this->client->request('PUT', self::$COUNTRY . '/25', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'code' => 'TE',
                'name' => 'Test',
                'nationality' => 'Testien',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Country',
            '@type' => 'Country',
            'code' => 'TE',
            'name' => 'Test',
            'nationality' => 'Testien',
        ]);

        $countryRepository = $this->get(CountryRepository::class);
        $country = $countryRepository->findOneBy(['name' => 'Test']);
        $this->assertNotNull($country);
    }

}
