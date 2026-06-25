<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Api\Country;

use App\Repository\CountryRepository;
use App\Tests\Exercise\AbstractApiTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractTestCountry extends AbstractApiTestCase
{
    protected function postNew(): ResponseInterface
    {
        $body = [
            "code" => 'uz',
            "name" => 'Uzbekistan',
            "nationality" => "Uzbekistani"
        ];

        $this->logIn("kevin@drosalys.fr");
        return $this->requestAsLoggedIn('POST', 'api/countries', ['json' => $body]);
    }
}
