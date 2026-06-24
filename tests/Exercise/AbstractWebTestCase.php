<?php

declare(strict_types=1);

namespace App\Tests\Exercise;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractWebTestCase extends WebTestCase
{
    static string $HOME = '/';

    protected string $defaultUrl = "/";
    protected KernelBrowser|AbstractBrowser|null $client;
    protected Crawler $crawler;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', $this->defaultUrl);
    }

    protected function logIn(string $email): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $email]);
        $this->client->loginUser($user);

        $this->client->request('GET', '/profil');
        $this->assertResponseIsSuccessful();
    }
}
