<?php

namespace App\Tests\Controller\Api\User;

use App\Tests\AbstractApiTestCaseTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserMeControllerTest extends AbstractApiTestCaseTest
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->defaultUrl = self::$ME;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    function testUserMeOk(): void
    {
        $response = $this->loginAndAccessRoute('kevin@drosalys.fr', '12345');

        $data = json_decode($response->getContent(), true);

        $this->assertSame('kevin@drosalys.fr', $data['email']);
        $this->assertSame('ktourret', $data['name']);
        $this->assertSame('kevin_t', $data['nickname']);
        $this->assertNull($data['profileImage']);
        $this->assertSame(5000000, $data['wallet']);
    }
}
