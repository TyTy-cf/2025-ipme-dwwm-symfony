<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Api;

use App\Repository\CountryRepository;
use App\Tests\Exercise\AbstractApiTestCase;
use Doctrine\ORM\EntityManagerInterface;

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

        // 1. Request

        $response = $this->postNew();
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

    // DELETE

    public function testDeleteSuccess(): void
    {
        $countryRepository = $this->getContainer()->get(CountryRepository::class);
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        // 1. Post new

        $this->postNew();
        $this->assertResponseIsSuccessful();

        // 2. Get country

        $country = $countryRepository->findOneBy(['code' => 'uz']);
        $countryId = $country->getId();
        $this->assertNotNull($country);

        // 3. Delete

        $route = 'api/countries/' . $countryId;
        $this->requestAsLoggedIn('DELETE', $route);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(204);

        // 4. Verify in database

        $country = $countryRepository->findOneBy(['id' => $countryId]);
        $this->assertNull($country);
    }

    // Private

    private function postNew(): void
    {
        $body = [
            "code" => 'uz',
            "name" => 'Uzbekistan',
            "nationality" => "Uzbekistani"
        ];

        $this->logIn("kevin@drosalys.fr");
        $this->requestAsLoggedIn('POST', 'api/countries', ['json' => $body]);
    }
}
