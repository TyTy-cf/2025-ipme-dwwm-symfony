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

class PatchControllerTest extends AbstractApiTestCaseTest
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
    public function testPatchCountryOk(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $this->client->request('PATCH', self::$COUNTRY . '/23', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/merge-patch+json'
            ],
            'json' => [
                'code' => 'TE'
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Country',
            '@type' => 'Country',
            'code' => 'TE',
            'name' => 'Suède',
            'nationality' => 'Suèdois',
        ]);

        $countryRepository = $this->get(CountryRepository::class);
        $country = $countryRepository->findOneBy(['code' => 'TE']);
        $this->assertNotNull($country);

        $this->client->request('PATCH', self::$COUNTRY . '/23', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/merge-patch+json'
            ],
            'json' => [
                'code' => 'se'
            ],
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testPatchCountryAuthKo(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '123456');

        $this->client->request('PATCH', self::$COUNTRY, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/merge-patch+json',
            ],
            'json' => [
                'code' => 'TT',
            ],
        ]);
        $this->assertResponseStatusCodeSame(405);
    }
}
