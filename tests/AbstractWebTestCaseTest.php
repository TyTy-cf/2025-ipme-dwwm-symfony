<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

class AbstractWebTestCaseTest extends WebTestCase
{
    static string $HOME = '/';
    static string $REGISTER = '/inscription';
    static string $PROFILE = '/profil/morgan93';

    protected string $defaultUrl;
    protected KernelBrowser|AbstractBrowser|null $client;
    protected Crawler $crawler;
    protected ?UserRepository $userRepository = null;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', $this->defaultUrl);
    }

    public function testAccessOk(): void
    {
        $this->assertResponseIsSuccessful();
    }

    protected function login(?string $email = null): void
    {
        if ($this->userRepository === null) {
            $this->userRepository = static::getContainer()->get(UserRepository::class);
        }

        if (null !== $user = $this->userRepository->findOneBy(['email' => $email])) {
            $this->client->loginUser($user);
        }
    }

    protected function tearDown(): void
    {
        $em = static::getContainer()->get(EntityManagerInterface::class);

        $user = $em->getRepository(User::class)
            ->findOneBy(['email' => 'test@test.test']);

        if ($user !== null) {
            $em->remove($user);
            $em->flush();
        }

        parent::tearDown();
    }
}
