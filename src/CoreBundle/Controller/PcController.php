<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use CoreBundle\Entity\Sujet;
use CoreBundle\Form\Type\SujetType;

class PcController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/pc", name="pc")
     */
      public function indexAction(Request $request)
      {
          $pc = $this->getDoctrine()->getManager()->getRepository('ForumsBundle:Sujet')->getSujetPC();
          return $this->render('Forums/PC/index.html.twig', array(
            'pc' => $pc
          ));
      }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/pc/add", name="pc_add")
     */
      public function addAction(Request $request)
      {
        $s_PC = new Sujet();
        $s_PC->setDateCreation(new \Datetime);
        $s_PC->setCategory('Pc');
        $user = $this->getUser();
        $s_PC->setAuteur($user);

        /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle
        puis on fait le lien requête <-> formulaire */
        $form_pc = $this->createForm(SujetType::class, $s_PC);
        $form_pc->handleRequest($request);

        /* On vérifie que les données sont valides, on les persist, on enregistre le tout
        et on redirige vers l'index du forums */
            if($form_pc->isValid()){
              $em = $this->getDoctrine()->getManager();
              $em->persist($s_PC);
              $em->flush();
              return $this->redirectToRoute('pc');
            }

        return $this->render('Forums/Action/add.html.twig', array(
          'pc' => $s_PC,
          'form' => $form_pc->createView()
        ));
      }
}
