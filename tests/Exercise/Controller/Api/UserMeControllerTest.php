<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Controller\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use PHPUnit\Framework\Attributes\IgnoreDeprecations;

#[IgnoreDeprecations]
class UserMeControllerTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/login_check', [
            'json' => [
                'email' => 'kevin@drosalys.fr',
                'password' => '12345'
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }
}
