<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;

class AbstractApiTestCaseTest extends ApiTestCase
{
    static $LOGIN_CHECK = '/api/login_check';
    static $USER_ME = '/api/user/me';

    static $GET = 'GET';
    protected string $defaultUrl;
    protected Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->request('GET', $this->defaultUrl);
    }

    protected function getAuthToken(string $email, string $password): string
    {
        $response= $this->client->request('POST', self::$LOGIN_CHECK, ['json' => ['email' => $email, 'password' => $password],]);
        return $response->toArray()['token'];
    }

    protected function testAuthenticatedEndpoint(string $userEmail, string $password, string $method, string $url): array
    {
        $token = $this->getAuthToken($userEmail, $password);
        $response = $this->client->request($method, $url, [
            'headers' => ['Authorization' => 'Bearer ' . $token],
        ]);
        $this->assertResponseIsSuccessful();
        return json_decode($response->getContent(), true);
    }

}
