<?php

namespace App\Tests\Correction\Controller\Back;

use App\Tests\Correction\AbstractWebTestCaseTest;
use PHPUnit\Framework\Attributes\DataProvider;

class AdminControllerTest extends AbstractWebTestCaseTest
{

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->skipAccessTest = true;
    }

    #[DataProvider('getDataForTest')]
    public function testAccessRoute(?string $email, string $route, int $statusExpected): void
    {
        $this->login($email);
        $this->client->request('GET', $route);
        $this->assertResponseStatusCodeSame($statusExpected);
    }

    public static function getDataForTest(): array
    {
        $data = [];
        $routes = [
            '/admin',
            '/admin/game',
            '/admin/game/new',
            '/admin/publisher/new',
            '/admin/country',
            '/admin/country/new',
            '/admin/category/nouvelle'
        ];

        $roles = [[null, 302], ['kevin@drosalys.fr', 200], ['zprosacco@hotmail.com', 403]];

        foreach ($roles as $role) {
            foreach ($routes as $route) {
                $key = 'Access ' . $route . ' for ' . $role[0] . ' is ' . $role[1];
                $data[$key] = [$role[0], $route, $role[1]];
            }
        }

        return $data;
    }

}
