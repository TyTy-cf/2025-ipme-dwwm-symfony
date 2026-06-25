<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Api;

use App\Tests\Exercise\AbstractApiTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class AccessControlTest extends AbstractApiTestCase
{
    #[TestWith(['POST', 'api/games'])]
    #[TestWith(['POST', 'api/reviews/1'])]
    #[TestWith(['POST', 'api/countries'])]
    #[TestWith(['POST', 'api/categories'])]
    #[TestWith(['POST', 'api/publishers'])]
    #[TestWith(['PUT', 'api/countries/1'])]
    #[TestWith(['PUT', 'api/categories/1'])]
    #[TestWith(['PUT', 'api/publishers/1'])]
    #[TestWith(['PATCH', 'api/countries/1'])]
    #[TestWith(['PATCH', 'api/categories/1'])]
    #[TestWith(['PATCH', 'api/publishers/1'])]
    #[TestWith(['DELETE', 'api/countries/1'])]
    #[TestWith(['DELETE', 'api/categories/1'])]
    public function testFailsOnLoggedOut(string $method, string $endpoint): void
    {
        $this->client->request($method, $endpoint);
        $this->assertResponseStatusCodeSame(401);
    }


    #[TestWith(['POST', 'api/games'])]
    #[TestWith(['POST', 'api/countries'])]
    #[TestWith(['POST', 'api/categories'])]
    #[TestWith(['POST', 'api/publishers'])]
    #[TestWith(['PUT', 'api/countries/1'])]
    #[TestWith(['PUT', 'api/categories/1'])]
    #[TestWith(['PUT', 'api/publishers/1'])]
    #[TestWith(['PATCH', 'api/countries/1'])]
    #[TestWith(['PATCH', 'api/categories/1'])]
    #[TestWith(['PATCH', 'api/publishers/1'])]
    #[TestWith(['DELETE', 'api/countries/1'])]
    #[TestWith(['DELETE', 'api/categories/1'])]
    public function testForbiddenOnPlainUser(string $method, string $endpoint): void
    {
        $this->logIn("alivia.cremin@torp.com");
        $this->requestAsLoggedIn($method, $endpoint);
        $this->assertResponseStatusCodeSame(403);
    }
}
