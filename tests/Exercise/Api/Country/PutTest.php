<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Api\Country;

use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;

class PutTest extends AbstractTestCountry
{
    public function testPutSuccess(): void
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

        // 3. Put

        $body = [
            'code' => 'va',
            'name' => 'Vatican City',
            'nationality' => 'Vaticaneer'
        ];

        $route = 'api/countries/' . $countryId;
        $response = $this->requestAsLoggedIn('PUT', $route, ['json' => $body]);
        $this->assertResponseIsSuccessful();

        // 4. Verify in database

        $country = $countryRepository->findOneBy(['id' => $countryId]);
        $this->assertEquals('Vatican City', $country->getName());
        $this->assertEquals('Vaticaneer', $country->getNationality());
        $this->assertEquals('va', $country->getCode());

        // 5. Cleanup

        $entityManager->remove($country);
        $entityManager->flush();
    }
}
