<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

    public function deleteAction(Request $request, $id)
    {
      /* Attention ! Cette action n'est pas réversible ! */
      $usermanager = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('UserBundle:User')
                          ->deleteUser($id);
      $request->getSession()->getFlashBag()->add('success', "L'utilisateur avec l'id" . $id . " a bien été supprimé");
      return $this->redirectToRoute('back_office');
    }
}
