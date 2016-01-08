<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use ForumsBundle\Entity\Sujet;
use ForumsBundle\Entity\Message;
use ForumsBundle\Form\Type\SujetType;
use ForumsBundle\Form\Type\MessageType;

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

      $msg_Sujet = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('ForumsBundle:Message')
                        ->findBy(array('sujet' => $sujet));


      return $this->render('ForumsBundle::view.html.twig', array(
        'sujet' => $sujet,
        'messages' => $msg_Sujet
      ));
    }

    public function messageAction(Sujet $sujet, Request $request)
    {
      $message = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('ForumsBundle:Message');

      $message = new Message();
      $message->setDateMessage(new \Datetime);
      $message->setSujet($sujet);

      /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
      requête <-> formulaire */
      $form_message = $this->createForm(MessageType::class, $message);
      $form_message->handleRequest($request);

      /* On vérifie que les données sont valides, on les persist, on enregistre le tout
      et on redirige vers l'index du forums */
          if($form_message->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
          }
      return $this->render('ForumsBundle::add_message.html.twig', array(
        'form' => $form_message->createView()
      ));
    }

    public function updateAction()
    {

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
