<?php

namespace App\Tests\Controller\Api\Country;

use App\Tests\AbstractApiTestCaseTest;
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
    public function testCountryPost(): void
    {
        $this->loginAndAccessRoute('kevin@drosalys.fr', '12345');

        $response = $this->client->request('POST', self::$COUNTRY, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
            'json' => [
                'code' => 'TE',
                'name' => 'Test',
                'nationality' => 'test',
            ],
        ]);
        dump($response);

//        $this->assertResponseIsSuccessful();
    }
}
