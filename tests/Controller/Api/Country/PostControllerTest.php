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

class PostControllerTest extends AbstractApiTestCaseTest
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
    public function testPostCountryOk(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $this->client->request('POST', self::$COUNTRY, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/ld+json'
            ],
            'json' => [
                'code' => 'TE',
                'name' => 'Test',
                'nationality' => 'test',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Country',
            '@type' => 'Country',
            'code' => 'TE',
            'name' => 'Test',
            'nationality' => 'test',
        ]);

        $countryRepository = $this->get(CountryRepository::class);
        $country = $countryRepository->findOneBy(['code' => 'TE']);
        $this->assertNotNull($country);

        $em = $this->get(EntityManagerInterface::class);
        $em->remove($country);
        $em->flush();
    }

    /**
     * @throws TransportExceptionInterface
     */
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
