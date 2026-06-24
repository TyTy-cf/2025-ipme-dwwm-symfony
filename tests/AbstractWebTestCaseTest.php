<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

class AbstractWebTestCaseTest extends WebTestCase
{
    static string $HOME = '/';
    static string $REGISTER = '/inscription';

    protected string $defaultUrl;
    protected KernelBrowser|AbstractBrowser|null $client;
    protected Crawler $crawler;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', $this->defaultUrl);
    }

    public function testAccessOk(): void
    {
        $this->assertResponseIsSuccessful();
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
