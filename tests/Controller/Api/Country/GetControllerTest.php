<?php

namespace App\Tests\Controller\Api\Country;

use App\Tests\AbstractApiTestCaseTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetControllerTest extends AbstractApiTestCaseTest
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
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testGetCountriesOk(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $this->client->request('GET', self::$COUNTRY, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/ld+json'
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Country',
            '@id' => '/api/countries',
            '@type' => 'Collection',
            'member' => []
        ]);
    }
}
