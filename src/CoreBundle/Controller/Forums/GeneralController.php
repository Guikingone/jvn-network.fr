<?php

namespace CoreBundle\Controller\Forums;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use CoreBundle\Entity\Sujet;
use CoreBundle\Form\Type\SujetType;

class GeneralController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/general", name="general")
     */
      public function indexAction(Request $request)
      {
          $general = $this->get('core.back')->indexForums('GENERAL');
          return $this->render('Forums/General/index.html.twig', array(
            'general' => $general
          ));
      }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/general/add", name="general_add")
     */
      public function addAction(Request $request)
      {
        $s_General = new Sujet();
        $s_General->setDateCreation(new \Datetime);
        $s_General->setCategory('General');
        $user = $this->getUser();
        $s_General->setAuteur($user);

        /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
        requête <-> formulaire */
        $form_general = $this->createForm(SujetType::class, $s_General);
        $form_general->handleRequest($request);

        /* On vérifie que les données sont valides, on les persist, on enregistre le tout
        et on redirige vers l'index du forums */
            if($form_general->isValid()){
              $em = $this->getDoctrine()->getManager();
              $em->persist($s_General);
              $em->flush();
              return $this->redirectToRoute('general');
            }

        return $this->render('Forums/Action/add.html.twig', array(
          'general' => $general,
          'form' => $form_general->createView()
        ));
      }
}
