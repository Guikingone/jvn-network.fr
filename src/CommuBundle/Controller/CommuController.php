<?php

namespace CommuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

      return $this->render('CommuBundle:Tchat:index.html.twig', array(
        'tchat' => $tchat
      ));
    }
}
