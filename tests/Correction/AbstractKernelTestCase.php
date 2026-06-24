<?php

namespace App\Tests\Correction;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class AbstractKernelTestCase extends KernelTestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);

        $requestStack = static::getContainer()->get(RequestStack::class);
        $requestStack->push($request);
    }

    protected function get(string $className): null|object
    {
        return static::getContainer()->get($className);
    }
}
