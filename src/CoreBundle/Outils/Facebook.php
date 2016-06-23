<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 22/06/2016
 * Time: 19:01
 */

namespace CoreBundle\Outils;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Symfony\Component\Routing\Router;

class Facebook
{
    /**
     * @return \Facebook\Facebook
     */
    public function createFacebook()
    {
        return $facebook = new \Facebook\Facebook([
            'app_id' => "%facebook_app_id%",
            'app_secret' => "%facebook_app_secret%",
            'default_graph_version' => 'v2.2',
        ]);
    }

    /**
     * @return \Facebook\Authentication\AccessToken|null
     */
    public function checkAccess()
    {
        $facebook = $this->createFacebook();
        try{
            $userId = $facebook->getRedirectLoginHelper()->getAccessToken();
        }catch (FacebookResponseException $e){
            echo 'Oops, looks like something went wrong :' . $e->getMessage();
            exit;
        }catch (FacebookSDKException $e){
            echo 'Oops, looks like something went wrong :' . $e->getMessage();
            exit;
        }
        return $userId;
    }
}