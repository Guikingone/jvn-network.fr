<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testCoreLink()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/');
                    $client->request('POST', '/contact');
                    $client->request('POST', '/mentions');
                    $client->request('POST', '/propos');
                    $client->request('POST', '/equipe');
                    $client->request('POST', '/krma');
                    $client->request('POST', '/membre');
                    $client->request('POST', '/communaute');
                    $client->request('POST', '/forums');
                    $client->request('POST', '/boutique');
                    $client->request('POST', '/console');
    }

    public function testBlogLink()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/blog/krma/1/hello-world');
        $crawler = $client->request('GET', '/blog/equipe/1/hello-World');
        $crawler = $client->request('GET', '/blog/membre/1/hello_world');
        $crawler = $client->request('POST', '/krma/admin');
        $crawler = $client->request('POST', '/article/2/hello-world');
    }
}
