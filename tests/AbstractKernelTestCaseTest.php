<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

class AbstractKernelTestCaseTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected function getRepository(string $class)
    {
        return static::getContainer()->get($class);
    }

    protected function getService(string $class)
    {
        return static::getContainer()->get($class);
    }

}
