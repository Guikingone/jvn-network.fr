<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use ForumsBundle\Entity\Sujet;
use ForumsBundle\Form\Type\SujetType;

class GeneralController extends Controller {

  public function indexAction(Request $request)
  {
      $general = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('ForumsBundle:Sujet')
                      ->getSujetGeneral();

      return $this->render('ForumsBundle:General:index.html.twig', array(
        'general' => $general
      ));
  }

  public function viewAction(Sujet $sujet)
  {
    /* on récupère le sujet selon son ID, on retourne le tout via une boucle for */
    $sujet = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('ForumsBundle:Sujet')
                 ->find($sujet);


    return $this->render('ForumsBundle:General:view.html.twig', array(
      'sujet' => $sujet
    ));
  }

  public function addAction(Request $request)
  {
    $general = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('ForumsBundle:Sujet')
                    ->getSujetGeneral();

    $s_General = new Sujet();
    $s_General->setDateCreation(new \Datetime);
    $s_General->setCategory('General');

    /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
    requête <-> formulaire */
    $form_general = $this->createForm(SujetType::class, $s_General);
    $form_general->handleRequest($request);

    /* On vérifie que les données sont valides, on les persist, on enregistre le tout et on renvoit un message
    flash afin de valider l'enregistrement du sujet */
        if($form_general->isValid()){
          $em = $this->getDoctrine()->getManager();
          $em->persist($s_General);
          $em->flush();
          $request->getSession()->getFlashBag()->add('success_forums', "Sujet enregistré");
        }

    return $this->render('ForumsBundle:General:index.html.twig', array(
      'general' => $general,
      'form' => $form_general->createView()
    ));
  }
}
