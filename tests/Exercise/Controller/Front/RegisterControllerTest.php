<?php

declare(strict_types=1);

namespace App\Tests\Exercise\Controller\Front;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Exercise\AbstractWebTestCase;

class RegisterControllerTest extends AbstractWebTestCase
{
    protected function setUp(): void
    {
        $this->defaultUrl = self::$REGISTER;
        parent::setUp();
    }

    public function testFormExists(): void
    {
        $form = $this->crawler->filter('.form-steamish')->form();

        $this->assertNotNull($form['user[nickname]']);
        $this->assertNotNull($form['user[name]']);
        $this->assertNotNull($form['user[email]']);
        $this->assertNotNull($form['user[password][first]']);
        $this->assertNotNull($form['user[password][second]']);
    }

    public function testNewRegistration(): void
    {
        $form = $this->crawler->filter('.form-steamish')->form();

        $form['user[nickname]'] = 'gambas';
        $form['user[name]'] = 'Gambas Goubard';
        $form['user[email]'] = 'g.goubard@gmail.com';
        $form['user[password][first]'] = 'babajtm63';
        $form['user[password][second]'] = 'babajtm63';

        $this->client->submit($form);

        /** @var UserRepository $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $form->get('user[email]')->getValue()]);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testBadRegistration(): void
    {
        $form = $this->crawler->filter('.form-steamish')->form();

        $form['user[nickname]'] = 'gorbisBoulain';
        $form['user[name]'] = 'Gorbis D. Boulain';
        $form['user[email]'] = 'gdb.bourlingueur@gmail.com';
        $form['user[password][first]'] = 'lksdljkndslksd';
        $form['user[password][second]'] = 'ld,fld,fmdl,fmdls,f';

        $this->client->submit($form);
        $this->assertResponseIsUnprocessable();

        /** @var UserRepository $userRepository */
        $userRepository = self::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => $form->get('user[email]')->getValue()]);

        $this->assertNull($user);
    }
}
