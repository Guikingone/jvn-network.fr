<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumsController extends Controller
{
    public function indexAction()
    {
        return $this->render('ForumsBundle::index.html.twig');
    }

    public function viewAction()
    {
      return $this->render('ForumsBundle::view.html.twig', array(
        'id' => $id
      ));
    }

    public function generalAction()
    {
      $general = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('ForumsBundle:Sujet')
                      ->getSujetGeneral();

      return $this->render('ForumsBundle:General:index.html.twig', array(
        'general' => $general
      ));
    }

    public function consolesAction()
    {
      $consoles = $this->getDoctrine()
                       ->getManager()
                       ->getRepository('ForumsBundle:Sujet')
                       ->getSujetConsoles();
      return $this->render('ForumsBundle:Consoles:index.html.twig', array(
        'consoles' => $consoles
      ));
    }

    public function pcAction()
    {
      return $this->render('ForumsBundle:PC:index.html.twig', array(
        'pc' => $pc
      ));
    }

    public function adminAction()
    {
      return $this->render('ForumsBundle:Admin:index.html.twig', array(
        'admin' => $admin
      ));
    }
}
