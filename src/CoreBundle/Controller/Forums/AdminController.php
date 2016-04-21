<?php

namespace CoreBundle\Controller\Forums;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use ForumsBundle\Entity\Sujet;
use ForumsBundle\Form\Type\SujetType;

class AdminController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/administration", name="admin")
     */
      public function indexAction(Request $request)
      {
          $admin = $this->getDoctrine()->getManager()->getRepository('ForumsBundle:Sujet')->getSujetAdmin();
          return $this->render('Forums/Admin/index.html.twig', array(
            'admin' => $admin
          ));
      }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/admin/add", name="admin_add")
     */
      public function addAction(Request $request)
      {
        $s_Admin = new Sujet();
        $s_Admin->setDateCreation(new \Datetime);
        $s_Admin->setCategory('Admin');
        $user = $this->getUser();
        $s_Admin->setAuteur($user);

        /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
        requête <-> formulaire */
        $form_admin = $this->createForm(SujetType::class, $s_Admin);
        $form_admin->handleRequest($request);

        /* On vérifie que les données sont valides, on les persist, on enregistre le tout
        et on redirige vers l'index du forums */
            if($form_admin->isValid()){
              $em = $this->getDoctrine()->getManager();
              $em->persist($s_Admin);
              $em->flush();
              return $this->redirectToRoute('admin');
            }

        return $this->render('Forums/Action/add.html.twig', array(
          'admin' => $s_Admin,
          'form' => $form_admin->createView()
        ));
      }
}
