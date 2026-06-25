<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Api;

use App\Tests\Exercise\AbstractApiTestCase;

class GameTest extends AbstractApiTestCase
{
    public function testGetCollection()
    {
        $response = $this->client->request('GET', 'api/games');
        $data = $this->json($response);

        $this->assertResponseIsSuccessful();

        $this->assertIsInt($data['totalItems']);
        $this->assertNotNull($data['member'][0]['@id']);
        $this->assertNotNull($data['member'][0]['@type']);
        $this->assertNotNull($data['member'][0]['name']);
        $this->assertNotNull($data['member'][0]['price']);
        $this->assertNotNull($data['member'][0]['slug']);
        $this->assertNotNull($data['member'][0]['thumbnailCover']);
    }
}
