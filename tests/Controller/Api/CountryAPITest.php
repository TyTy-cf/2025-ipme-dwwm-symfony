<?php

namespace App\Tests\Controller\Api;

use App\Entity\Game;
use App\Repository\CountryRepository;
use App\Repository\GameRepository;
use App\Tests\AbstractAPITestCase;
use App\Tests\AbstractKernelTestCaseTest;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\Attributes\TestWith;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class CountryAPITest extends AbstractAPITestCase
{
    public function setUp(string|null $repositoryClass = null): void
    {
        parent::setUp(CountryRepository::class);
    }

    #[TestWith(['/api/countries/1', ['code', 'name', 'id', 'nationality', 'urlFlag', 'slug']])]
    #[TestWith(['/api/countries/12', ['code', 'name', 'id', 'nationality', 'urlFlag', 'slug']])]
    #[TestWith(['/api/countries/5', ['code', 'name', 'id', 'nationality', 'urlFlag', 'slug']])]
    #[TestWith(['/api/countries', ['code', 'name', 'nationality', 'urlFlag', 'slug'], true])]
    public function testPublicGetOk(string $url, array $propertiesToFind,bool $isCollection = false): void
    {
        $this->testEndpointGET($url, $propertiesToFind, $isCollection);
    }

    #[TestWith([
        '/api/countries',
        'POST',
        [ 'code' => 'BE', 'name' => 'Belgique', 'nationality' => 'Belge' ],
        ['code', 'name', 'id', 'nationality', 'urlFlag', 'slug'],
        'kevin@drosalys.fr', '12345'
        ])]
    public function testContentSentOk(string $url, string $method, array $associativeSent, array $propertiesToFind, string $email = "", string $password = ""): void
    {
        $this->testContentSentEndpoint($url, $method, $associativeSent, $propertiesToFind, $email, $password);
    }
}
