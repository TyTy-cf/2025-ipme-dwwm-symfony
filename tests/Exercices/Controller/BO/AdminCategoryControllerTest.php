<?php

namespace App\Tests\Exercices\Controller\BO;

use App\Tests\Exercices\AbstractWebTestCaseTest;

class AdminCategoryControllerTest extends AbstractWebTestCaseTest
{
    protected function setUp(): void
    {
        $this->defaultUrl = self::$ADMIN_CATEGORY;
        parent::setUp();
    }
}
