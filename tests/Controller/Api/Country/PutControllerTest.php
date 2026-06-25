<?php

namespace App\Tests\Controller\Api\Country;

use App\Repository\CountryRepository;
use App\Tests\AbstractApiTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PutControllerTest extends AbstractApiTestCaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$COUNTRY;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testPutCountryOk(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $this->client->request('PUT', self::$COUNTRY . '/54', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'code' => 'TT',
                'name' => 'Test Test',
                'nationality' => 'Test'
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Country',
            '@type' => 'Country',
            'code' => 'TT',
            'name' => 'Test Test',
            'nationality' => 'Test',
        ]);

        $countryRepository = $this->get(CountryRepository::class);
        $country = $countryRepository->findOneBy(['name' => 'Test Test']);
        $this->assertNotNull($country);

        $this->client->request('PUT', self::$COUNTRY . '/54', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'code' => 'TT',
                'name' => 'TestTest',
                'nationality' => 'Test'
            ],
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testPutCountryAuthKo(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '123456');

        $this->client->request('PUT', self::$COUNTRY, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/ld+json',
            ],
            'json' => [
                'code' => 'TT',
                'name' => 'Test Test',
                'nationality' => 'Test'
            ],
        ]);
        $this->assertResponseStatusCodeSame(405);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[TestWith([['code' => 'TE','name' => '', 'nationality' => 'Test']], 'Test with empty name')]
    #[TestWith([['code' => 'TE','name' => 'Test',]], 'Test with empty nationality')]
    #[TestWith([['code' => '','name' => '', 'nationality' => 'Test',]], 'Test with empty code')]
    public function testPutCountryValidationKo(array $data): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $this->client->request('PUT', self::$COUNTRY, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $data,
        ]);

        $this->assertResponseStatusCodeSame(405);
    }
}
