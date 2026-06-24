<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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
}
