<?php

declare(strict_types=1);

namespace App\Tests\Exercise;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class AbstractKernelTestCase extends KernelTestCase
{
    private Container $container;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();
    }

    protected function get(string $className): null|object
    {
        return $this->container->get($className);
    }

    protected function mockSession(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);

        $requestStack = static::getContainer()->get(RequestStack::class);
        $requestStack->push($request);
    }
}
