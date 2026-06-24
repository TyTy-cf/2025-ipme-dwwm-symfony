<?php

namespace App\Tests\Correction;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractKernelTestCase extends WebTestCase
{

    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected function get(string $className): null|object
    {
        return static::getContainer()->get($className);
    }
}
