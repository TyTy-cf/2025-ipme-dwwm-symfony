<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Symfony\Bundle\Test\Response as ApiResponse;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AbstractApiTestCaseTest extends ApiTestCase
{
    static string $LOGIN_CHECK = '/api/login_check';
    static string $ME = '/api/user/me';
    static string $COUNTRY = '/api/countries';

    protected ?string $token = null;
    protected string $defaultUrl;
    protected bool $skipAccessTest = false;
    protected Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function getAuthToken(string $email, string $password): ?string {
        try {
            /** @var Response $response */
            $response = $this->client->request('POST', self::$LOGIN_CHECK, [
                'json' => [
                    'email' => $email,
                    'password' => $password
                ],
            ]);

            $data = json_decode($response->getContent(), true);
            $this->token = $data['token'] ?? null;
            return $data['token'] ?? null;
        } catch (ClientException $e) {
            return null;
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function loginAndAccessRoute(string $email, string $password): ApiResponse {
        $token = $this->getAuthToken($email, $password);

        /** @var ApiResponse $response */
        $response = $this->client->request('GET', $this->defaultUrl, [
            'auth_bearer' => $token,
        ]);

        return $response;
    }
}
