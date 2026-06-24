<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityAccessControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    #[TestWith(['/admin/category/nouvelle'])]
    #[TestWith(['/admin'])]
    #[TestWith(['/admin/country/new'])]
    #[TestWith(['/admin/country/edit/1'])]
    #[TestWith(['/admin/game'])]
    #[TestWith(['/admin/publisher'])]
    public function testAnonymousAccessKO(string $url)
    {
        $this->client->request('GET', $url);
        self::assertResponseStatusCodeSame(302);
    }

    #[TestWith(['/admin/category/nouvelle'])]
    #[TestWith(['/admin'])]
    #[TestWith(['/admin/country/new'])]
    #[TestWith(['/admin/country/edit/1'])]
    #[TestWith(['/admin/game'])]
    #[TestWith(['/admin/publisher'])]
    public function testUserAccessKO(string $url)
    {
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['id' => 1]);
        $this->client->loginUser($user);
        $this->client->request('GET', $url);
        self::assertResponseStatusCodeSame(403);
    }

    #[TestWith(['/admin/category/nouvelle'])]
    #[TestWith(['/admin'])]
    #[TestWith(['/admin/country/new'])]
    #[TestWith(['/admin/country/edit/1'])]
    #[TestWith(['/admin/game'])]
    #[TestWith(['/admin/publisher'])]
    public function testAdminAccessOK(string $url)
    {
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'kevin@drosalys.fr']);
        $this->client->loginUser($user);
        $this->client->request('GET', $url);
        self::assertResponseIsSuccessful();
    }
}
