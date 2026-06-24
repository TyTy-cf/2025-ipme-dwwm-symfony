<?php

namespace App\Tests\Controller\Front;

use App\Entity\User;
use App\Repository\CountryRepository;
use App\Repository\UserRepository;
use App\Tests\AbstractWebTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use function PHPUnit\Framework\assertNotNull;

class CountryControllerTest extends WebTestCase
{
    private User $user;
    private Crawler $crawler;

    private CountryRepository $countryRepository;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'kevin@drosalys.fr']);
        $this->client->loginUser($this->user);
        $this->countryRepository = static::getContainer()->get(CountryRepository::class);
        $this->em = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testIndexPagination()
    {
        $this->crawler = $this->client->request('GET', '/admin/country');
        self::assertResponseIsSuccessful();

        $count = $this->crawler->filter('tr')->count();

        self::assertEquals(13, $count);

        $this->crawler = $this->client->request('GET', '/admin/country?page=2');
        self::assertResponseIsSuccessful();

        $count = $this->crawler->filter('tr')->count();

        self::assertTrue($count <= 13);
    }

    public function testAddCountryForm()
    {
        $this->crawler = $this->client->request('GET', '/admin/country/new');
        self::assertResponseIsSuccessful();

        self::assertSelectorExists('form[name="country"]');
        self::assertSelectorExists('input[name="country[name]"]');
        self::assertSelectorExists('input[name="country[nationality]"]');
        self::assertSelectorExists('input[name="country[code]"]');

        $form = $this->crawler->selectButton('Créer')->form();

        $form['country[name]'] = 'Test';
        $form['country[nationality]'] = 'Testais';
        $form['country[code]'] = 'TE';

        $form = $this->client->submit($form);

        self::assertResponseRedirects('/admin/country');

        $country = $this->countryRepository->findOneBy(['name' => 'Test']);

        assertNotNull($country);

        $this->em->remove($country);
        $this->em->flush();
    }

    public function testEditCountryForm()
    {
        $this->crawler = $this->client->request('GET', '/admin/country/edit/19');
        self::assertResponseIsSuccessful();

        self::assertSelectorExists('form[name="country"]');
        self::assertSelectorExists('input[name="country[name]"]');
        self::assertSelectorExists('input[name="country[nationality]"]');
        self::assertSelectorExists('input[name="country[code]"]');

        $form = $this->crawler->selectButton('Créer')->form();

        $form['country[nationality]'] = 'Allemandais';

        $form = $this->client->submit($form);

        self::assertResponseRedirects('/admin/country');

        $country = $this->countryRepository->findOneBy(['name' => 'Allemandais']);

        assertNotNull($country);

        $country->setNationality('Allemand');

        $this->em->flush();
    }
}
