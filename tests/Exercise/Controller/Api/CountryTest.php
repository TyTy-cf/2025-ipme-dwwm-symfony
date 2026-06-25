<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Controller\Api;

use App\Repository\CountryRepository;
use App\Tests\Exercise\AbstractApiTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;

class CountryTest extends AbstractApiTestCase
{
    // GET

    public function testGetItem(): void
    {
        $response = $this->client->request('GET', 'api/countries/1');
        $this->assertResponseIsSuccessful();

        $data = $this->json($response);
        $this->assertIsString($data['code']);
        $this->assertIsString($data['name']);
        $this->assertIsString($data['slug']);
        $this->assertIsString($data['urlFlag']);
        $this->assertIsString($data['nationality']);
    }

    public function testGetCollection(): void
    {
        $response = $this->client->request('GET', 'api/countries');
        $this->assertResponseIsSuccessful();

        $data = $this->json($response);
        $this->assertIsInt($data['totalItems']);
        $this->assertIsArray($data['member']);

        $member = $data['member'][0];
        $this->assertIsString($member['code']);
        $this->assertIsString($member['name']);
        $this->assertIsString($member['slug']);
        $this->assertIsString($member['urlFlag']);
        $this->assertIsString($member['nationality']);
    }

    // POST

    public function testPostFailsOnEmptyContent(): void
    {
        $this->logIn("kevin@drosalys.fr");
        $this->requestAsLoggedIn('POST', 'api/countries', ['json' => []]);
        $this->assertResponseStatusCodeSame(500);
    }

    public function testPostSuccess(): void
    {
        $countryRepository = $this->getContainer()->get(CountryRepository::class);
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $body = [
            "code" => 'uz',
            "name" => 'Uzbekistan',
            "nationality" => "Uzbekistani"
        ];

        // 1. Request

        $this->logIn("kevin@drosalys.fr");
        $response = $this->requestAsLoggedIn('POST', 'api/countries', ['json' => $body]);
        $this->assertResponseIsSuccessful();

        // 2. Output data

        $data = $this->json($response);
        $this->assertIsString($data['code']);
        $this->assertIsString($data['name']);
        $this->assertIsString($data['slug']);
        $this->assertIsString($data['urlFlag']);
        $this->assertIsString($data['nationality']);

        // 3. Database content

        $country = $countryRepository->findOneBy(['code' => 'uz']);
        $this->assertNotNull($country);

        // 4. Cleanup

        $entityManager->remove($country);
        $entityManager->flush();
    }
}
