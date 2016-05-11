<?php

namespace CoreBundle\Controller\Forums;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use CoreBundle\Entity\Sujet;

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
     * This controller is used by the entire forum part of the application, he use the Back Service in order to access
     * to the repository needed and make the current action.
     */

    /**
     * @param Sujet $sujet
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/view/{id}", requirements={"id": "\d+"}, name="forums_view")
     * @Template("Forums\Action\view.html.twig")
     */
    public function viewAction(Sujet $sujet, Request $request)
    {
        $form = $this->get('core.back')->viewSujet($request, $sujet);
        $message = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('CoreBundle:Message')
                        ->findBy(array('sujet' => $sujet));
        return array('sujet' => $sujet, 'message' => $message, 'form' => $form->createView());
    }

    /**
     * @param Request $request
     * @param Sujet $sujet
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/update/{id}", requirements={"id": "\d+"}, name="forums_update")
     * @Template("Forums\Action\update.html.twig")
     */
    public function updateAction(Request $request, Sujet $sujet)
    {
        $form = $this->get('core.back')->updateSujet($request, $sujet);
        return array('form' => $form->createView(), 'sujet' => $sujet);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delete/{id}", requirements={"id": "\d+"}, name="forums_delete")
     */
    public function deleteAction($id)
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
