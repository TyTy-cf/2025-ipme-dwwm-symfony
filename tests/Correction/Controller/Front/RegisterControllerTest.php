<?php

namespace App\Tests\Correction\Controller\Front;

use App\Repository\UserRepository;
use App\Tests\Correction\AbstractWebTestCaseTest;
use Doctrine\ORM\EntityManagerInterface;

class RegisterControllerTest extends AbstractWebTestCaseTest
{

    protected function setUp(): void
    {
        $this->defaultUrl = self::$REGISTER;
        parent::setUp();
    }

    public function testRegisterFormInputs(): void
    {
        $this->assertSelectorExists('input[name="user[nickname]"]');
        $this->assertSelectorExists('input[name="user[name]"]');
        $this->assertSelectorExists('input[name="user[email]"]');
        $this->assertSelectorExists('input[name="user[password][first]"]');
        $this->assertSelectorExists('input[name="user[password][second]"]');
    }

    public function testRegisterOk(): void
    {
        $form = $this->crawler->selectButton('Valider mon inscription')->form();
        $form['user[nickname]'] = 'testNickname';
        $form['user[name]'] = 'testName';
        $form['user[email]'] = 'testName@mail.com';
        $form['user[password][first]'] = '123';
        $form['user[password][second]'] = '123';

        $this->client->submit($form);

        $this->assertResponseRedirects('/');

        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $user = $this->userRepository->findOneBy(['email' => 'testName@mail.com']);
        $this->assertNotNull($user);

        $em = static::getContainer()->get(EntityManagerInterface::class);
        $em->remove($user);
        $em->flush();
    }

    public function testRegisterKo(): void
    {
        $form = $this->crawler->selectButton('Valider mon inscription')->form();
        $form['user[nickname]'] = 'testNickname';
        $form['user[name]'] = 'testName';
        $form['user[email]'] = 'testName@mail.com';
        $form['user[password][first]'] = '1234';
        $form['user[password][second]'] = '123';

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(422);

        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $user = $this->userRepository->findOneBy(['email' => 'testName@mail.com']);
        $this->assertNull($user);
    }

}
