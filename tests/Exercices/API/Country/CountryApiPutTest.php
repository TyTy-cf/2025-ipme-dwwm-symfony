<?php

namespace App\Tests\Exercices\API\Country;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Tests\Exercices\AbstractApiTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;

class CountryApiPutTest extends AbstractApiTestCaseTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$COUNTRIES_URL;
    }

    public function testPutCountryOk(): void
    {
        $countryRepository = $this->get(CountryRepository::class);
        $country = $countryRepository->findOneBy(["id" => 1]);
        $body = [
            'code'  => $country->getCode(),
            'name' => 'Espagne',
            'nationality' => $country->getNationality(),
        ];

        $response = $this->testAuthenticatedEndpoint('kevin@drosalys.fr', '12345', self::$PUT, $body, $country->getId());

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

    #[TestWith([["code" => "", "name" => "France", "nationality" =>  "Français",]])]
    #[TestWith([["code" => "fr", "name" => "", "nationality" =>  "Français",]])]
    #[TestWith([["code" => "fr", "name" => "France", "nationality" =>  "",]])]
    public function testPutCountryKo(array $bodyData): void
    {
        $body = [
            'code'  => $bodyData['code'],
            'name' => $bodyData['name'],
            'nationality' => $bodyData['nationality'],
        ];
        $this->testAuthenticatedEndpoint('kevin@drosalys.fr', '12345', self::$PUT, $body, 1);

        // check response status code
        $this->assertResponseStatusCodeSame(422);
    }
}
