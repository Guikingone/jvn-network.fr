<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    public function viewAction(Sujet $sujet, Request $request)
    {
      /* On récupère le sujet selon son ID, on retourne le tout via une boucle for, si inexistant, une erreur 404
      est lancée, par la suite, on recherche les messages affiliés à ce sujet, on les affichera via une boucle for */
      $sujet = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('ForumsBundle:Sujet')
                    ->find($sujet);

      /** On récupère les messages liés au sujet via le sujet et on y joint les
      messages afin de pouvoir faire sujet->getMessages(), une fois effectuée,
      on affichera tout ceci via une boucle for dans la vue */
      $msg = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('ForumsBundle:Message')
                  ->findBy(array('sujet' => $sujet));

      $message = new Message();
      $message->setDateMessage(new \Datetime);
      $message->setSujet($sujet);
      $user = $this->getUser();
      $message->setAuteur($user);
      $formMessage = $this->createForm(MessageType::class, $message);
      $formMessage->handleRequest($request);

        if($formMessage->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($message);
          $em->flush();
        }

      return $this->render('ForumsBundle::view.html.twig', array(
        'sujet' => $sujet,
        'message' => $msg,
        'form' => $formMessage->createView()
      ));
    }

    public function updateAction(Request $request, $id)
    {
      /* On récupère l'entité via l'ID, si le sujet n'existe pas, on renvoit un message d'erreur,
      on ouvre le formulaire, on valide, on affiche un message d'info afin
      de valider l'opération et on redirige vers la page d'accueil */
      $us = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('ForumsBundle:Sujet')
                 ->find($id);

      if (null === $us) {
        throw new NotFoundHttpException("Le sujet d'id ".$id." n'existe pas.");
      }

      $form = $this->createForm(SujetType::class, $us);
      $form->handleRequest($request);


      /* Ici, on se contente de vérifier que tout est valide, on ne persise pas car Doctrine connaît l'entité,
      une fois que tout est terminé, on affiche un message de succés et on redirige vers l'article en question */

      if($form->isValid())
      {
        $us = $this->getDoctrine()
                   ->getManager()
                   ->flush();

        $request->getSession()->getFlashBag()->add('success', "Le sujet" . $id . "a bien été modifiée");
        return $this->redirectToRoute('forums_home');
      }
      return $this->render('ForumsBundle::update.html.twig', array(
        'form' => $form->createView(),
        'sujet' => $us
      ));
    }

    public function deleteAction(Request $request, $id)
    {
      /* On récupère le service Purge afin de supprimer le sujet selon son ID, une fois supprimé, on renvoit
      un message flash afin de valider la suppression */
      $delete = $this->get('corebundle.purge_all');
      $delete->purgeSujet($id);
      $request->getSession()->getFlashBag()->add('success_forums', 'Sujet supprimé !');
      return $this->redirectToRoute('forums_home');
    }

    public function deleteMessageAction(Request $request, $id)
    {
      $delete = $this->get('corebundle.purge_all');
      $delete->purgeMessage($id);
      $request->getSession()->getFlashBag()->add('success_forums', 'Message supprimé !');
      return $this->redirectToRoute('forums_home');
    }
}
