<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function backAction()
    {
      $usermanager = $this->get('fos_user.user_manager');
      $users = $usermanager->findUsers();

      return $this->render('UserBundle:Back:index.html.twig', array(
          'user' => $users
        ));
    }

    public function deleteAction(Request $request, $id)
    {
      /* Attention ! Cette action n'est pas réversible ! */
      $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->deleteUser($id);
      $this->addFlash('success', "L'utilisateur avec l'id" . $id . " a bien été supprimé");
      return $this->redirectToRoute('equipe');
    }
}
