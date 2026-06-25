<?php

namespace App\Tests\Correction\Controller\Api\Country;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Tests\Correction\AbstractApiTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;

class PutControllerTest extends AbstractApiTestCaseTest
{

    private int $id;

    protected function setUp(): void
    {
        parent::setUp();
        $country = (new Country())
            ->setName('Danemark')
            ->setNationality('Danois')
            ->setCode('dk');

        $em = $this->get(EntityManagerInterface::class);
        $em->persist($country);
        $em->flush();

        $this->id = $country->getId();
        $this->defaultUrl = self::$COUNTRY . '/' . $this->id;
        $this->classRepo = CountryRepository::class;
    }

    #[TestWith([['code' => 'dk','name' => 'Danemark', 'nationality' => 'Danish'], 200,
        [
            'code',
            'name',
            'nationality',
            'urlFlag',
            'slug'
        ]
    ], 'Test OK')]
    #[TestWith([['code' => 'dk','name' => 'Danemark', 'nationality' => ''], 422], 'Test with empty nationality')]
    #[TestWith([['code' => '','name' => 'Danemark', 'nationality' => 'Danois'], 422], 'Test with empty code')]
    #[TestWith([['code' => 'dk','name' => '', 'nationality' => 'Danois'], 422], 'Test with empty name')]
    public function testPutCountry(array $data, int $expectedCode, array $jsonKeys = []): void
    {
        $this->postPut($data, $expectedCode, 'PUT', $jsonKeys);
    }

    protected function tearDown(): void
    {
        $repository = $this->get($this->classRepo);
        $item = $repository->find($this->id);

        $em = $this->get(EntityManagerInterface::class);
        $em->remove($item);
        $em->flush();
    }

}
