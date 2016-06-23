<?php

namespace CoreBundle\Controller\Web\Facebook;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/connect/facebook", name="facebook_connect")
     */
    public function redirectToAuthorisationAction()
    {
        $facebook = $this->get('core.facebook')->createFacebook();

        $redirectUrl = $this->generateUrl('facebook_authorize_redirect', array(), true);
        $url = $facebook->getRedirectLoginHelper()->getLoginUrl(array(
            'redirect_uri' => $redirectUrl,
            'scope' => 'email', 'user_likes', 'publish_actions'
        ));
        return $this->redirect($url);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/connect/facebook/check", name="facebook_connect_check")
     */
    public function receiveAuthorisationCode()
    {
        $facebook_user_id = $this->get('core.facebook')->checkAccess();
        $user = $this->getUser();
        $user->setFacebook_id($facebook_user_id);
        if(!$user->facebook_id){
            return $this->redirect('home');
        }
        return $this->render('User/error_facebook_id.html.twig');
    }
}
