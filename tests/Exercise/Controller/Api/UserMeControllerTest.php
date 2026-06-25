<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Controller\Api;

use App\Tests\Exercise\AbstractApiTestCase;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[IgnoreDeprecations]
class UserMeControllerTest extends AbstractApiTestCase
{
    public function testUserMeRouteWorks()
    {
        $this->logIn("kevin@drosalys.fr");

        $response = $this->requestAsLoggedIn('GET', 'api/user/me');
        $data = $this->json($response);

        $this->assertResponseIsSuccessful();
        $this->assertNotNull($data['wallet']);
    }

    public function testUserMeReturnsNullWhenLoggedOut()
    {
        $response = $this->client->request('GET', 'api/user/me');
        $json = $this->json($response);

        $this->assertResponseIsSuccessful();
        $this->assertNull($json);
    }
}
