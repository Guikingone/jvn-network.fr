<?php
/**
 * Created by PhpStorm.
 * User: Guillaume Loulier | guillaume.loulier[at]hotmail.fr
 * Date: 22/06/2016
 * Time: 19:01.
 */
namespace CoreBundle\Outils;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class Facebook
{
    /**
     * @return \Facebook\Facebook
     */
    public function createFacebook()
    {
        return new \Facebook\Facebook([
            'app_id' => '%facebook_app_id%',
            'app_secret' => '%facebook_app_secret%',
            'default_graph_version' => 'v2.2',
        ]);
    }

    /**
     * @return \Facebook\Authentication\AccessToken|null
     */
    public function checkAccess()
    {
        $facebook = $this->createFacebook();
        try {
            $userId = $facebook->getRedirectLoginHelper()->getAccessToken();
        } catch (FacebookResponseException $e) {
            echo 'Oops, looks like something went wrong :'.$e->getMessage();
        } catch (FacebookSDKException $e) {
            echo 'Oops, looks like something went wrong :'.$e->getMessage();
        }

        return $userId;
    }
}
