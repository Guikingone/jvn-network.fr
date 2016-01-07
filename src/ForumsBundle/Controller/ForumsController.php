<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use ForumsBundle\Entity\Sujet;
use ForumsBundle\Form\Type\SujetType;

class ForumsController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('ForumsBundle::index.html.twig');
    }

    public function viewAction(Sujet $sujet)
    {
      /* on récupère le sujet selon son ID, on retourne le tout via une boucle for, par la suite,
      on recherche les messages affiliés à ce sujet, on les affichera via une boucle for */
      $sujet = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('ForumsBundle:Sujet')
                   ->find($sujet);

      $msg_Sujet = $sujet->getRepository('ForumsBundle:Message')
                         ->findBy(array('sujet' => $sujet));


      return $this->render('ForumsBundle::view.html.twig', array(
        'sujet' => $sujet,
        'messages' => $msg_Sujet
      ));
    }

    public function deleteAction(Request $request, $id)
    {
      /* On récupère le service Purge afin de supprimer le sujet selon son ID, une fois supprimé, on renvoit
      un message flash afin de valider la suppression */
      $delete = $this->get('forumsbundle.forums_purger');
      $delete->purge($id);

      $request->getSession()->getFlashBag()->add('success_forums', 'Sujet supprimé !');

      return $this->redirectToRoute('forums_home');
    }
}
