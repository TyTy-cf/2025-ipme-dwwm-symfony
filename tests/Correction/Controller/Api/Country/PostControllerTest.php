<?php

namespace App\Tests\Correction\Controller\Api\Country;

use App\Repository\CountryRepository;
use App\Tests\Correction\AbstractApiTestCaseTest;
use PHPUnit\Framework\Attributes\TestWith;

class PostControllerTest extends AbstractApiTestCaseTest
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$COUNTRY;
        $this->classRepo = CountryRepository::class;
    }

    #[TestWith([['code' => 'TE','name' => 'test', 'nationality' => 'Test'], 201,
        [
            'code',
            'name',
            'nationality',
            'urlFlag',
            'slug'
        ]
    ], 'Test OK')]
    #[TestWith([['code' => 'TE','name' => '', 'nationality' => 'Test'], 422], 'Test with empty name')]
    #[TestWith([['code' => 'TE','name' => 'Test'], 422], 'Test with empty nationality')]
    #[TestWith([['code' => '','name' => '', 'nationality' => 'Test'], 422], 'Test with empty code')]
    public function testPostCountryOk(array $data, int $expectedCode, array $jsonKeys = []): void
    {
        $this->testPostOk($data, $expectedCode, $jsonKeys);
    }

}
