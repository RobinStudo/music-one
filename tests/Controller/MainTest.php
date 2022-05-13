<?php
namespace App\Tests\Controller;

use App\Model\Contact;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainTest extends WebTestCase
{
    public function testHome(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur MusicOne');
    }

    public function testContact(): void
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $client->submitForm('Envoyer', [
            'contact[name]' => 'Test',
            'contact[email]' => 'test@test.com',
            'contact[phone]' => '+33679898788',
            'contact[topic]' => Contact::TOPIC['Question générale sur la plateforme'],
            'contact[message]' => 'Bonjour, ceci est un test blablablablablabla',
        ]);

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.notification-notice', 'Message envoyé');
    }
}
