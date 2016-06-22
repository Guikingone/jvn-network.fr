<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToAuthorisationAction()
    {
        $facebook = $this->createFacebook();
        $redirectUrl = $this->generateUrl('facebookg_autorize_redirect', array(), true);
        $url = $facebook->getLoginUrl(array(
            'result_uri' => '',
            'scope' => array('email', 'publish_actions')
        ));

        return $this->redirectToRoute($url);
    }


    public function backAction()
    {
      $usermanager = $this->get('fos_user.user_manager');
      $users = $usermanager->findUsers();

      return $this->render('UserBundle:Back:index.html.twig', array(
          'user' => $users
        ));
    }

    public function deleteAction($id)
    {
      /* Attention ! Cette action n'est pas réversible ! */
      $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->deleteUser($id);
      $this->addFlash('success', "L'utilisateur avec l'id" . $id . " a bien été supprimé");
      return $this->redirectToRoute('equipe');
    }

    /**
     * @return \Facebook
     */
    private function createFacebook()
    {
        return new \Facebook([
            'app_id' => "%facebook_app_id%",
            'app_secret' => "%facebook_app_secret%",
            'default_graph_version' => 'v2.2',
        ]);
    }
}
