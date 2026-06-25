<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Api;

use App\Tests\Exercise\AbstractApiTestCase;

class GameTest extends AbstractApiTestCase
{
    public function testGetCollection()
    {
        $response = $this->client->request('GET', 'api/games');
        $this->assertResponseIsSuccessful();

        $data = $this->json($response);
        $this->assertIsInt($data['totalItems']);
        $this->assertIsArray($data['member']);

        $member = $data['member'][0];
        $this->assertNotNull($member['name']);
        $this->assertNotNull($member['price']);
        $this->assertNotNull($member['slug']);
        $this->assertNotNull($member['thumbnailCover']);
    }
}
