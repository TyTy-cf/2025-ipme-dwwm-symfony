<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Api\Country;

use App\Repository\CountryRepository;

class DeleteTest extends AbstractTestCountry
{
    public function testDeleteSuccess(): void
    {
        $countryRepository = $this->getContainer()->get(CountryRepository::class);

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
}
