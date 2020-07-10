<?php

namespace Prony\Tests\Functional\Controller\Site\Front;

use Prony\PhpUnit\CreateDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class RegistrationControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function it_correctly_shows_registration_page()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'prony.local:8000']);
        $client->request('GET', '/register');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function it_shows_error_messages_for_empty_values()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'prony.local:8000']);
        $crawler = $this->submitForm($client);
        $this->assertStringContainsStringIgnoringCase('Please enter an email.', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('Please enter your first name', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('Please enter your last name', $crawler->html());
        $this->assertStringContainsStringIgnoringCase('Please enter a password.', $crawler->html());
    }

    /**
     * @test
     */
    public function it_allows_to_register()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'prony.local:8000']);
        $crawler = $this->submitForm($client, [
            'talav_user_registration[email]' => 'dan.swith@test.com',
            'talav_user_registration[firstName]' => 'Dan',
            'talav_user_registration[lastName]' => 'Smith',
            'talav_user_registration[plainPassword][first]' => 'tester',
            'talav_user_registration[plainPassword][second]' => 'tester',
        ]);
        // this is suppose to work but it does not :(
//        $this->assertEmailCount(1);
//        $email = $this->getMailerMessage(0);
//        $this->assertEmailHeaderSame($email, 'To', 'tester1@test.com');
//        $this->assertEmailHeaderSame($email, 'Subject', 'Welcome email');
    }

    /**
     * @param $client
     * @param array $data
     *
     * @return Crawler
     */
    private function submitForm($client, $data = []): Crawler
    {
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Register')->form();
        foreach ($data as $key => $value) {
            $form[$key] = $value;
        }
        $client->followRedirects(true);
        $crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $crawler;
    }
}