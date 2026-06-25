<?php

namespace App\Tests\Correction\Controller\Api;

use App\Tests\Correction\AbstractApiTestCaseTest;

class LoginCheckTest extends AbstractApiTestCaseTest
{

    function testLoginCheckOk(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');
        $this->assertNotNull($token);
    }

    function testLoginCheckKo(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '123456');
        $this->assertNull($token);
    }

}
