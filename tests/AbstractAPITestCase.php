<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function PHPUnit\Framework\assertArrayHasKey;

class AbstractAPITestCase extends ApiTestCase
{
    static string $LOGIN = '/api/login_check';
    static string $ME = '/api/user/me';

    protected Client $client;
    protected EntityManagerInterface $em;
    protected EntityRepository|null $repo;

    protected function setUp(string|null $repositoryClass = null): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
        if ($repositoryClass !== null && $repositoryClass !== '')
        {
            $this->repo = static::getContainer()->get($repositoryClass);
        }
    }

    protected function getAuthToken(string $email, string $password): string
    {
        $response = $this->client->request('POST', self::$LOGIN, [
            'json' => ['email' => $email, 'password' => $password],
        ]);
        return $response->toArray()['token'];
    }
    protected function testEndpointGET(string $url, array $propertiesToFind, bool $isCollection, string $email = "", string $password = ""): ResponseInterface
    {
        if ($email !== "" && $password !== "")
        {
            $token = $this->getAuthToken($email, $password);
            $response = $this->client->request('GET', $url, [
                'headers' => ['Authorization' => 'Bearer ' . $token],
            ]);
        }
        else
        {
            $response = $this->client->request('GET', $url);
        }
        $this->assertResponseIsSuccessful();

        $content = json_decode($response->getContent(), true);

        if ($isCollection)
        {
            foreach ($content['member'] as $item)
            {
                foreach ($propertiesToFind as $property)
                {
                    assertArrayHasKey($property, $item);
                }
            }
        }
        else
        {
            foreach ($propertiesToFind as $property)
            {
                assertArrayHasKey($property, $content);
            }
        }

        return $response;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $associativeSent
     * @param array $propertiesToFind
     * @param string $email
     * @param string $password
     * @return ResponseInterface
     */
    protected function testContentSentEndpoint(string $url, string $method, array $associativeSent, array $propertiesToFind, string $email = "", string $password = ""): ResponseInterface
    {
        $options = [
            'json' => $associativeSent,
        ];
        $options['headers'] = [
            'Accept' => 'application/ld+json',
            'Content-Type' => 'application/ld+json',
        ];

        if ($email !== "" && $password !== "")
        {
            $token = $this->getAuthToken($email, $password);
            $options['headers']['Authorization'] = 'Bearer ' . $token;
        }

        $id = null;
        $object = null;
        //Store object before changes
        if ($method === "PATCH" || $method === "PUT" || $method === "DELETE")
        {
            $str = explode('/', $url);
            $id = $str[count($str) - 1];
            $object = clone $this->repo->find($id);
        }

        $response = $this->client->request($method, $url, $options);

        $this->assertResponseIsSuccessful();

        $content = json_decode($response->getContent(), true);

        foreach ($propertiesToFind as $property)
        {
            assertArrayHasKey($property, $content);
        }

        $entity = $this->repo->findOneBy($associativeSent);

        if ($method === "POST")
        {
            $this->em->remove($entity);
        }
        elseif ($method === "PATCH" || $method === "PUT")
        {
            $newObject = $this->repo->find($id);
            $newObject = $object;

            $this->em->persist($newObject);
        }
        elseif ($method === "DELETE")
        {
            $this->em->persist($object);
        }
        $this->em->flush();
        return $response;
    }

}
