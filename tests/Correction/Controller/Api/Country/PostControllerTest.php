<?php

namespace App\Tests\Correction\Controller\Api\Country;

use App\Repository\CountryRepository;
use App\Tests\Correction\AbstractApiTestCaseTest;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;

class PostControllerTest extends AbstractApiTestCaseTest
{

    public function testPostCountryOk(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $response = $this->client->request('POST', self::$COUNTRY, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'code' => 'TE',
                'name' => 'Test',
                'nationality' => 'test',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);

        $countryRepository = $this->get(CountryRepository::class);
        $country = $countryRepository->findOneBy(['code' => 'TE']);
        $this->assertNotNull($country);

        $em = $this->get(EntityManagerInterface::class);
        $em->remove($country);
        $em->flush();
    }

    #[TestWith([['code' => 'TE','name' => '', 'nationality' => 'Test']], 'Test with empty name')]
    #[TestWith([['code' => 'TE','name' => 'Test',]], 'Test with empty nationality')]
    #[TestWith([['code' => '','name' => '', 'nationality' => 'Test',]], 'Test with empty code')]
    public function testPostCountryValidationKo(array $data): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $this->client->request('POST', self::$COUNTRY, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $data,
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

}
