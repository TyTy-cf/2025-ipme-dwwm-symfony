<?php

declare(strict_types=1);

namespace App\Tests\Correction;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

class AbstractWebTestCaseTest extends WebTestCase
{
    static string $HOME = '/';

    protected string $defaultUrl;
    protected KernelBrowser|AbstractBrowser|null $client;
    protected Crawler $crawler;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', $this->defaultUrl);
    }

}
