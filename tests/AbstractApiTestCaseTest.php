<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AbstractApiTestCaseTest extends ApiTestCase
{
    static $LOGIN_CHECK = '/api/login_check';
    static $USER_ME = '/api/user/me';

    static $COUNTRIES_URL = '/api/countries';

    static $GET = 'GET';
    static $POST = 'POST';
    static $PUT = 'PUT';
    static $PATCH = 'PATCH';
    static $DELETE = 'DELETE';
    protected string $defaultUrl;
    protected Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function getAuthToken(string $email, string $password): string|null
    {
        try {


            $response = $this->client->request('POST', self::$LOGIN_CHECK, ['json' => ['email' => $email, 'password' => $password],]);
            $data = json_decode($response->getContent(), true);
            return $data['token'];
        }catch (ClientExceptionInterface $e) {
            return null;
        }
    }

    protected function testAuthenticatedEndpoint(string $userEmail, string $password, string $method, array $body = null, int $id = null): ResponseInterface
    {
        $token = $this->getAuthToken($userEmail, $password);

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
        ];

        if (!empty($body)) {
            $options['json'] = $body;
        }

        $url = $this->defaultUrl;

        if(null !== $id) {
            $url .= '/' . $id;
        }

        return $this->client->request($method, $url, $options);
    }

    protected function get(string $class): ?object
    {
        return static::getContainer()->get($class);
    }
}
