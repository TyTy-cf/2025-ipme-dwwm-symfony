<?php

namespace App\Tests\Controller\Front;

use App\Entity\User;
use App\Tests\AbstractWebTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;

class RegisterControllerTest extends AbstractWebTestCaseTest
{
    protected function setUp(): void
    {
        $this->defaultUrl = self::$REGISTER;
        parent::setUp();
    }

    #[TestWith(['user[nickname]', 1, 'input'])]
    #[TestWith(['user[name]', 2, 'input'])]
    #[TestWith(['user[email]', 3, 'input'])]
    #[TestWith(['user[password][first]', 4, 'input'])]
    #[TestWith(['user[password][second]', 5, 'input'])]
    #[TestWith(['user[submit]', 2, 'button'])]
    public function testRegisterFormVisible(string $name, int $position, string $type): void
    {
        $form = $this->crawler->filter('.form-steamish');
        $this->assertCount(1, $form);

        $formItem = $this->crawler->filter($type)
            ->eq($position)
            ->attr('name');

        $this->assertEquals($name, $formItem);
    }

    public function testRegisterOk(): void
    {
        $form = $this->crawler->filter('[name="user"]')->form();

        $this->client->submit($form, [
            'user[nickname]' => 'Test',
            'user[name]' => 'Test',
            'user[email]' => 'test@test.test',
            'user[password][first]' => '12345',
            'user[password][second]' => '12345',
        ]);

        $this->assertResponseRedirects('/');
        $this->crawler = $this->client->followRedirect();

        $user =  static::getContainer()->get(EntityManagerInterface::class)
            ->getRepository(User::class)
            ->findOneBy(['email' => 'test@test.test']);
        $this->assertNotNull($user);

        $this->tearDown();
    }

    public function testRegisterKo(): void
    {
        $form = $this->crawler->filter('[name="user"]')->form();

        $this->client->submit($form, [
            'user[nickname]' => 'Test',
            'user[name]' => 'Test',
            'user[email]' => 'test@test.test',
            'user[password][first]' => '12345',
            'user[password][second]' => '123',
        ]);

        $this->assertResponseStatusCodeSame(422);
    }
}
