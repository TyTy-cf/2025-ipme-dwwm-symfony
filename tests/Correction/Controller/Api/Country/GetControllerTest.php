<?php

namespace App\Tests\Correction\Controller\Api\Country;

use App\Entity\Country;
use App\Tests\Correction\AbstractApiTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;

class GetControllerTest extends AbstractApiTestCaseTest
{

    protected Country $country;

    protected function setUp(): void
    {
        parent::setUp();

        $em = static::getContainer()->get(EntityManagerInterface::class);

        $country = new Country();
        $country->setCode('TE');
        $country->setName('Test Country');

        $em->persist($country);
        $em->flush();

        $this->country = $country;
    }

    public function testGetCountryOk(): void
    {
        $token = $this->getAuthToken('kevin@drosalys.fr', '12345');

        $response = $this->client->request('GET', self::$COUNTRY . '/' . $this->country->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);


        $this->assertResponseStatusCodeSame(200);

        $data = $response->toArray();
        $this->assertSame('TE', $data['code']);
        $this->assertSame('Test Country', $data['name']);
    }

}
