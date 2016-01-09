<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function backAction()
    {
      /* On récupère la liste des utilisateurs inscrits */
      $usermanager = $this->get('fos_user.user_manager');
      $users = $usermanager->findUsers();

        return $this->render('UserBundle:Back:index.html.twig', array(
          'user' => $users
        ));
    }

    public function deleteUser()
    {
      
    }
}
