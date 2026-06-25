<?php

namespace App\Tests\Correction\Controller\Api\Category;

use App\Repository\CategoryRepository;
use App\Tests\Correction\AbstractApiTestCaseTest;
use PHPUnit\Framework\Attributes\TestWith;

class PostControllerTest extends AbstractApiTestCaseTest
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$CATEGORY;
        $this->classRepo = CategoryRepository::class;
    }

    #[TestWith([['image' => null,'name' => 'test'], 201,
        [
            'id',
            'name',
            'slug'
        ]
    ], 'Test OK without image')]
    #[TestWith([['image' => 'frerererer','name' => 'test'], 201,
        [
            'id',
            'image',
            'name',
            'slug'
        ]
    ], 'Test OK with image')]
    #[TestWith([['image' => null,'name' => ''], 422], 'Test with empty name')]
    public function testPostCategoryOk(array $data, int $expectedCode, array $jsonKeys = []): void
    {
        $this->testPostOk($data, $expectedCode, $jsonKeys);
    }

}
