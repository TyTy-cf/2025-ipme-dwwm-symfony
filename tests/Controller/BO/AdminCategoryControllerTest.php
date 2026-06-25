<?php

namespace App\Tests\Controller\BO;

use App\Tests\AbstractWebTestCaseTest;

class AdminCategoryControllerTest extends AbstractWebTestCaseTest
{
    protected function setUp(): void
    {
        $this->defaultUrl = self::$ADMIN_CATEGORY;
        parent::setUp();
    }
}
