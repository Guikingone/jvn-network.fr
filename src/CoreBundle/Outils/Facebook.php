<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 22/06/2016
 * Time: 19:01
 */

namespace CoreBundle\Outils;

use Symfony\Component\Routing\Router;

class Facebook
{
    /**
     * @return \Facebook\Facebook
     */
    public function createFacebook()
    {
        return new \Facebook\Facebook([
            'app_id' => "%facebook_app_id%",
            'app_secret' => "%facebook_app_secret%",
            'default_graph_version' => 'v2.2',
        ]);
    }
}