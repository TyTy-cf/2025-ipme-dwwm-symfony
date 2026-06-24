<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class AbstractKernelTestCaseTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected function assertRepositoryMethodCount(string $entityClass, string $method, int $limit = 9): void
    {
        $results = static::getContainer()->get(EntityManagerInterface::class)
            ->getRepository($entityClass)
            ->$method($limit);

        $this->assertNotEmpty($results);
        $this->assertCount($limit, $results);
    }

    protected function get(string $className): null|object
    {
        return static::getContainer()->get($className);
    }

    protected function setUpRequestStack(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);

        $requestStack = static::getContainer()->get(RequestStack::class);
        $requestStack->push($request);
    }
}
