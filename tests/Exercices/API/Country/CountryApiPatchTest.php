<?php

namespace App\Tests\Exercices\API\Country;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Tests\Exercices\AbstractApiTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;

class CountryApiPatchTest extends AbstractApiTestCaseTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$COUNTRIES_URL;
    }

    /*
     * TODO: Refacto PATCH et PUT, car ils partagent 90% de leur code. Extraire les bloc d'assert en fonctions utilitaires
     */

    public function testPatchCountryOk(): void
    {
        $countryRepository = $this->get(CountryRepository::class);
        $country = $countryRepository->findOneBy(["id" => 1]);
        $body = [
            'name' => 'Espagne',
        ];

        $response = $this->testAuthenticatedEndpoint('kevin@drosalys.fr', '12345', self::$PATCH, $body, $country->getId());

        // check response status code
        $this->assertResponseStatusCodeSame(200);

        // check response data
        $data = json_decode($response->getContent(), true);
        $this->assertSame('fr', $data['code']);
        $this->assertSame('Espagne', $data['name']);
        $this->assertSame('Français', $data['nationality']);
        $this->assertSame('espagne', $data['slug']);
        $this->assertSame('https://flagcdn.com/32x24/fr.png', $data['urlFlag']);

        // check entity insertion
        $insertedCountry = $countryRepository->findOneBy(["id" => 1]);
        $this->assertSame('fr', $insertedCountry->getCode());
        $this->assertSame('Espagne', $insertedCountry->getName());
        $this->assertSame('Français', $insertedCountry->getNationality());
        $this->assertSame('espagne', $insertedCountry->getSlug());
        $this->assertSame('https://flagcdn.com/32x24/fr.png', $insertedCountry->getUrlFlag());

        $entityManager = $this->get(EntityManagerInterface::class);

        // On reset
        $countryReset = $entityManager->find(Country::class, 1);

        if ($countryReset) {
            $countryReset->setName($country->getName());
            $entityManager->flush();
        }
    }
}
