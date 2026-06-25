<?php

declare(strict_types=1);

namespace App\Tests\Exercise;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Contracts\HttpClient\ResponseInterface;

#[IgnoreDeprecations]
abstract class AbstractApiTestCase extends ApiTestCase
{
    protected Container $container;
    protected Client $client;
    private string $token;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->client = static::createClient();
        $this->container = static::getContainer();
    }

    protected function logIn(string $email): void
    {
        $body = ['email' => $email, 'password' => '12345'];
        $response = $this->client->request('POST', '/api/login_check', ['json' => $body]);
        $json = $this->json($response);

        $this->token = $json['token'];
    }

    protected function requestAsLoggedIn(string $method, string $url, array $options = []): ResponseInterface
    {
        $options['headers'] = [
            ...($options['headers'] ?? []),
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/ld+json',
            'Content-Type' => 'application/ld+json'
        ];

        return $this->client->request($method, $url, $options);
    }

    protected function json(ResponseInterface $response): mixed
    {
        return json_decode($response->getContent(), true);
    }
}
