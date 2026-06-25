<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Api\Country;

class GetTest extends AbstractTestCountry
{
    public function testGetItem(): void
    {
        $response = $this->client->request('GET', 'api/countries/1');
        $this->assertResponseIsSuccessful();

        $data = $this->json($response);
        $this->assertIsString($data['code']);
        $this->assertIsString($data['name']);
        $this->assertIsString($data['slug']);
        $this->assertIsString($data['urlFlag']);
        $this->assertIsString($data['nationality']);
    }

    public function testGetCollection(): void
    {
        $response = $this->client->request('GET', 'api/countries');
        $this->assertResponseIsSuccessful();

        $data = $this->json($response);
        $this->assertIsInt($data['totalItems']);
        $this->assertIsArray($data['member']);

        $member = $data['member'][0];
        $this->assertIsString($member['code']);
        $this->assertIsString($member['name']);
        $this->assertIsString($member['slug']);
        $this->assertIsString($member['urlFlag']);
        $this->assertIsString($member['nationality']);
    }
}
