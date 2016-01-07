<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use ForumsBundle\Entity\Sujet;
use ForumsBundle\Form\Type\SujetType;

class ForumsController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('ForumsBundle::index.html.twig');
    }

    public function viewAction(Sujet $sujet)
    {
      /* on récupère le sujet selon son ID, on retourne le tout via une boucle for */
      $sujet = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('ForumsBundle:Sujet')
                   ->find($sujet);


      return $this->render('ForumsBundle::view.html.twig', array(
        'sujet' => $sujet
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
      $pc = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('ForumsBundle:Sujet')
                 ->getSujetPC();

      return $this->render('ForumsBundle:PC:index.html.twig', array(
        'pc' => $pc
      ));
    }

    public function adminAction()
    {
      $admin = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('ForumsBundle:Sujet')
                    ->getSujetAdmin();
                    
      return $this->render('ForumsBundle:Admin:index.html.twig', array(
        'admin' => $admin
      ));
    }
}
