<?php

namespace App\Tests\Controller\Front;

use App\Tests\AbstractWebTestCaseTest;
use PHPUnit\Framework\Attributes\TestWith;
use function PHPUnit\Framework\assertNotNull;

class RegisterControllerTest extends AbstractWebTestCaseTest
{

    protected function setUp(): void
    {
        $this->defaultUrl = self::$REGISTER;
        parent::setUp();
    }

    public function testRegisterInputs()
    {
        $form = $this->crawler->selectButton('Valider mon inscription')->form();

        assertNotNull($form['user[nickname]']);
        assertNotNull($form['user[name]']);
        assertNotNull($form['user[email]']);
        assertNotNull($form['user[password][first]']);
        assertNotNull($form['user[password][second]']);
    }

    public function testRegisterOK()
    {
        $form = $this->crawler->selectButton('Valider mon inscription')->form();
        $form['user[nickname]'] = 'testNickname';
        $form['user[name]'] = 'testName';
        $form['user[email]'] = uniqid() . '@mail.com';
        $form['user[password][first]'] = '123456';
        $form['user[password][second]'] = '123456';

        $this->client->submit($form);

        $this->assertResponseRedirects('/');
    }

    public function testRegisterKO()
    {
        $form = $this->crawler->selectButton('Valider mon inscription')->form();
        $form['user[nickname]'] = 'testNickname';
        $form['user[name]'] = 'testName';
        $form['user[email]'] = 'invalidEmail';
        $form['user[password][first]'] = '123456';
        $form['user[password][second]'] = '12345';

        $this->client->submit($form);

        self::assertResponseStatusCodeSame(422);
    }
}
