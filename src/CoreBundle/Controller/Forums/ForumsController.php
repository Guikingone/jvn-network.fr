<?php

namespace CoreBundle\Controller\Forums;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use CoreBundle\Entity\Sujet;
use CoreBundle\Entity\Message;
use CoreBundle\Form\Type\SujetType;
use CoreBundle\Form\Type\MessageType;

/**
 * @Route("/forums")
 */
class ForumsController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="forums")
     */
    public function indexAction()
    {
        return $this->render('Forums/index.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/administration", name="admin")
     * @Template("Forums\Admin\index.html.twig")
     */
    public function indexAdminAction(Request $request)
    {
        $admin = $this->get('core.back')->indexForums('ADMIN');
        $form = $this->get('core.back')->addSujet($request, 'ADMIN');
        return array('admin' => $admin, 'form' => $form->createView());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/console", name="forum_console")
     * @Template("Forums\Consoles\index.html.twig")
     */
    public function indexConsoleAction(Request $request)
    {
        $consoles = $this->get('core.back')->indexForums('CONSOLE');
        $form = $this->get('core.back')->addSujet($request, 'CONSOLE');
        return array('consoles' => $consoles, 'form' => $form->createView());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/general", name="general")
     * @Template("Forums\General\index.html.twig")
     */
    public function indexGeneralAction(Request $request)
    {
        $general = $this->get('core.back')->indexForums('GENERAL');
        $form = $this->get('core.back')->addSujet($request, 'GENERAL');
        return array('general' => $general, 'form' => $form->createView());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/pc", name="pc")
     * @Template("Forums\PC\index.html.twig")
     */
    public function indexPcAction(Request $request)
    {
        $pc = $this->get('core.back')->indexForums('PC');
        $form = $this->get('core.back')->addSujet($request, 'PC');
        return array('pc' => $pc, 'form' => $form->createView());
    }

    /**
     * This controller is used by the entire forum part of the application, he used the Back Service in order to access
     * to the repository needed.
     */

    /**
     * @param Sujet $sujet
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/view/{id}", requirements={"id": "\d+"}, name="forums_view")
     */
    public function viewAction(Sujet $sujet, Request $request)
    {
      $sujet = $this->getDoctrine()->getManager()->getRepository('ForumsBundle:Sujet')->find($sujet);

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

      return $this->render('Forums/Action/view.html.twig', array(
        'sujet' => $sujet,
        'message' => $msg,
        'form' => $formMessage->createView()
      ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @Route("/update/{id}", requirements={"id": "\d+"}, name="forums_update")
     */
    public function updateAction(Request $request, $id)
    {
      $us = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('ForumsBundle:Sujet')
                 ->find($id);

      if (null === $us) {
        throw new NotFoundHttpException("Le sujet d'id ".$id." n'existe pas.");
      }

      $form = $this->createForm(SujetType::class, $us);
      $form->handleRequest($request);

      if($form->isValid()) {
          $us = $this->getDoctrine()->getManager();
          $us->flush();
        $this->addFlash('success', "Le sujet" . $id . "a bien été modifiée");
        return $this->redirectToRoute('forums');
      }
      return $this->render('Forums/Action/update.html.twig', array(
        'form' => $form->createView(),
        'sujet' => $us
      ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delete/{id}", requirements={"id": "\d+"}, name="forums_delete")
     */
    public function deleteAction(Request $request, $id)
    {
      $this->get('core.back')->deleteSujet($id);
      return $this->redirectToRoute('forums');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delete/message/{id}", requirements={"id": "\d+"}, name="forums_delete_message")
     */
    public function deleteMessageAction($id)
    {
      $this->get('core.back')->deleteMessage($id);
      return $this->redirectToRoute('forums');
    }
}
