<?php

namespace CommuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;

class CommuController extends Controller
{
    public function indexAction()
    {
        return $this->render('CommuBundle::index.html.twig');
    }

    public function tchatAction(Request $request)
    {
      $tchat = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('CommuBundle:Tchat')
                    ->getTchat();

      return $this->render('CommuBundle::index.html.twig', array(
        'tchat' => $tchat
      ));
    }
}
