<?php

namespace Tests\AppBundle\Functionnal\Route;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RouteControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @return array
     *
     * Provide the different routes through the application
     */
    public function urlProvider()
    {
        return array(
            array('/'),
            array('/login'),
            array('/register/'),
            array('/krma'),
            array('/equipe'),
            array('/membre'),
            array('/krma/admin'),
            array('/equipe/admin'),
            array('/membre/admin'),
            array('/contact'),
            array('/mentions'),
            array('/communaute'),
            array('/propos'),
            array('/forums/'),
            array('/forums/administration'),
            array('/forums/console'),
            array('/forums/pc'),
            array('/forums/general'),
            array('/boutique')
        );
    }
}
