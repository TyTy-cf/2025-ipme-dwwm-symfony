<?php

namespace App\Tests\Controller\Api;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Tests\AbstractAPITestCase;
use App\Tests\AbstractKernelTestCaseTest;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Attributes\TestWith;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class AuthenticationAPITest extends AbstractAPITestCase
{
    public function setUp(string|null $repositoryClass = null): void
    {
        parent::setUp();
    }

    #[TestWith(['kevin@drosalys.fr', '12345'])]
    public function testLogin(string $email, string $password)
    {
        $token = $this->getAuthToken($email, $password);

        assertNotNull($token);
    }

    #[TestWith(['kevin@drosalys.fr', '12345'])]
    public function testProfileOK(string $email, string $password)
    {
        $token = $this->getAuthToken($email, $password);

        assertNotNull($token);

        $response = $this->testEndpointGET(self::$ME, [], false, $email, $password);

        $content = json_decode($response->getContent(), true);
        assertEquals($content['email'], "kevin@drosalys.fr");
        assertEquals($content['name'], "ktourret");
        assertEquals($content['nickname'], "kevin_t");
        assertEquals($content['profileImage'], null);
        assertEquals($content['wallet'], 5000000);
    }
}
