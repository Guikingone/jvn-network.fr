<?php

namespace CommuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CommuBundle\Entity\Tchat;
use CommuBundle\Form\Type\TchatType;

class CommuController extends Controller
{
    public function indexAction(Request $request)
    {
      $tchat = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CommuBundle:Tchat')
                    ->getTchat();

      $add = new Tchat();
      $add->setDateCreation(new \Datetime);

      $form_add = $this->createForm(TchatType::class, $add);
      $form_add->handleRequest($request);
      if($form_add->isValid()){
        $em = $this->getDoctrine()->getManager();
        $em->persist($add);
        $em->flush();
        return $this->redirectToRoute('commu_home');
      }

        return $this->render('CommuBundle::index.html.twig', array(
          'tchat' => $tchat,
          'form' => $form_add->createView()
        ));
    }
}
