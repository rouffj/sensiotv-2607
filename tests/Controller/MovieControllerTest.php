<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{

    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/movie/tt0242653');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Matrix Revolutions');

        $this->assertEquals('text/html; charset=UTF-8', $client->getResponse()->headers->get('Content-Type'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Matrix Revolutions', $crawler->filter('body h1')->text());
    }

    public function testGoToRegisterPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $client->clickLink('Register');

        $this->assertEquals('/register', $client->getRequest()->getRequestUri());

        $crawler = $client->submitForm('Register', [
            'register[firstName]' => 'Joseph',
            'register[lastName]' => 'ROUFF',
            'register[email]' => 'invalidEmail',
        ]);

        $this->assertCount(2, $crawler->filter('.form-error-message'), 'Error on email + terms should happen.');
    }

    /**
     * Unit test
     */
    public function testMyObject(): void
    {
        $user = new User();
        $user->setEmail('joseph@joseph.io');

        $this->assertEquals('joseph@joseph.io', $user->getEmail());
    }
}
