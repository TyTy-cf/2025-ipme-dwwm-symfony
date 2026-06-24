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

    #[TestWith(['Pseudo', 'user_nickname', 0])]
    #[TestWith(['Nom', 'user_name', 1])]
    #[TestWith(['Email', 'user_email', 2])]
    #[TestWith(['Mot de passe', 'user_password_first', 3])]
    #[TestWith(['Confirmez le mot de passe', 'user_password_second', 4])]
    public function testRenderingForm(string $expectedLabel, string $expectedInputAttr, int $position): void
    {
        $labels = $this->crawler->filter('.form-steamish label')->eq($position);

        $inputsAttr = $this->crawler->filter('.form-steamish input')->eq($position);

        $this->assertSame($expectedLabel, $labels->text());
        $this->assertSame($expectedInputAttr, $inputsAttr->attr('id'));
        $this->assertSame($inputsAttr->attr('id'), $labels->attr('for'));
    }

    public function testRegisterWithValidData(): void
    {
        $form = $this->crawler->selectButton('Valider mon inscription')->form();
        $userEmail = 'user.test@test.com';

        $form['user[nickname]'] = 'User';
        $form['user[name]'] = 'Test';
        $form['user[email]'] = $userEmail;
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';

        $this->client->submit($form);
        $this->assertResponseRedirects('/');
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $userEmail]);

        $this->assertNotNull($user);
        $entityManager->remove($user);
        $entityManager->flush();
    }

    public function testRegisterWithInvalidData(): void
    {
        $form= $this->crawler->selectButton('Valider mon inscription')->form();
        $form['user[nickname]'] = 'user';
        $form['user[name]'] = 'test';
        $form['user[email]'] = 'test@test.com';
        $form['user[password][first]'] = '1234';
        $form['user[password][second]'] = '5678';
        $this->client->submit($form);

        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertSame(422, $statusCode);
    }
}
