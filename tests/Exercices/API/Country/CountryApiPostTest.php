<?php

namespace App\Tests\Exercices\API\Country;

use App\Repository\CountryRepository;
use App\Tests\Exercices\AbstractApiTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;

class CountryApiPostTest extends AbstractApiTestCaseTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$COUNTRIES_URL;
    }

    public function testPostCountryOk()
    {

        $body = [
            "code" => "fk",
            "name" => "fake country",
            "nationality" =>  "fake country",
        ];

        $response = $this->testAuthenticatedEndpoint('kevin@drosalys.fr', '12345', self::$POST, $body);
        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('nationality', $data);
        $this->assertArrayHasKey('urlFlag', $data);
        $this->assertArrayHasKey('slug', $data);

        $this->assertResponseStatusCodeSame(201);

        $countryRepository = $this->get(CountryRepository::class);
        $country = $countryRepository->findOneBy(['code' => 'fk']);
        $this->assertNotNull($country);

        $em = $this->get(EntityManagerInterface::class);
        $em->remove($country);
        $em->flush();
    }

    #[TestWith([["code" => "", "name" => "fake country", "nationality" =>  "fake country",]])]
    #[TestWith([["code" => "fk", "name" => "", "nationality" =>  "fake country",]])]
    #[TestWith([["code" => "fk", "name" => "fake country", "nationality" =>  "",]])]
    public function testPostCountryKo()
    {

        $body = [
            "code" => "fk",
            "name" => "fake country",
            "nationality" =>  "",
        ];

        $this->testAuthenticatedEndpoint('kevin@drosalys.fr', '12345', self::$POST, $body);
        $this->assertResponseStatusCodeSame(422);
    }

    public function testPostCountryWithNoAdminUser()
    {

        $body = [
            "code" => "fk",
            "name" => "fake country",
            "nationality" =>  "fake country",
        ];

        $this->testAuthenticatedEndpoint('zprosacco@hotmail.com', '12345', self::$POST, $body);
        $this->assertResponseStatusCodeSame(403);
    }
}
