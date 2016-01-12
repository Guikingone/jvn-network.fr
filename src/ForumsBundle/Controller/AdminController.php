<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use ForumsBundle\Entity\Sujet;
use ForumsBundle\Form\Type\SujetType;

class AdminController extends Controller {

  public function indexAction(Request $request)
  {
      $admin = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('ForumsBundle:Sujet')
                      ->getSujetAdmin();

      return $this->render('ForumsBundle:Admin:index.html.twig', array(
        'admin' => $admin
      ));
  }

  public function addAction(Request $request)
  {
    $admin = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('ForumsBundle:Sujet')
                    ->getSujetAdmin();

    $s_Admin = new Sujet();
    $s_Admin->setDateCreation(new \Datetime);
    $s_Admin->setCategory('Admin');

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
          return $this->redirectToRoute('forums_admin');
        }

    return $this->render('ForumsBundle::add.html.twig', array(
      'admin' => $s_Admin,
      'form' => $form_admin->createView()
    ));
  }
}
