<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Api\Country;

use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostTest extends AbstractTestCountry
{
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
}
