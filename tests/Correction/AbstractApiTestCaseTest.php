<?php

declare(strict_types=1);

namespace App\Tests\Correction;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Symfony\Bundle\Test\Response as ApiResponse;

class AbstractApiTestCaseTest extends ApiTestCase
{
    static string $LOGIN_CHECK = '/api/login_check';
    static string $ME = '/api/user/me';
    static string $COUNTRY = '/api/countries';
    static string $CATEGORY = '/api/categories';

    protected string $defaultUrl;
    protected string $classRepo;
    protected bool $skipAccessTest = false;
    protected Client $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function get(string $class): ?object {
        return static::getContainer()->get($class);
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
            return $data['token'] ?? null;
        } catch (ClientException $e) {
            return null;
        }
    }

    protected function loginAndAccessRoute(string $email, string $password): ApiResponse {
        $token = $this->getAuthToken($email, $password);

        /** @var ApiResponse $response */
        $response = $this->client->request('GET', $this->defaultUrl, [
            'auth_bearer' => $token,
        ]);

        return $response;
    }

    protected function testPostOk(array $data, int $expectedCode, array $jsonKeys = []): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $response = $this->client->request('POST', $this->defaultUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            'json' => $data,
        ]);

        $this->assertResponseStatusCodeSame($expectedCode);

        if ($expectedCode === 201) {
            $dataDecoded = json_decode($response->getContent(), true);

            foreach ($jsonKeys as $jsonKey) {
                $this->assertArrayHasKey($jsonKey, $dataDecoded);
            }

            $repository = $this->get($this->classRepo);
            $item = $repository->findOneBy($data);
            $this->assertNotNull($item);

            $em = $this->get(EntityManagerInterface::class);
            $em->remove($item);
            $em->flush();
        }
    }

}
