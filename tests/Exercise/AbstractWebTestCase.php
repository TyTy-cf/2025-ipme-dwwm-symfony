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
    static string $REGISTER = '/inscription';
    static string $PROFILE = '/profile';

    protected ?string $defaultUrl = null;
    protected KernelBrowser|AbstractBrowser|null $client;
    protected Crawler $crawler;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        if ($this->defaultUrl) {
            $this->crawler = $this->client->request('GET', $this->defaultUrl);
        }
    }

    protected function logIn(string $email): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $email]);
        $this->client->loginUser($user);
    }
}
