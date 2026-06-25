<?php

namespace App\Tests\Exercices;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

abstract class AbstractKernelTestCaseTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }
    protected function getClass(string $class)
    {
        return static::getContainer()->get($class);
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
