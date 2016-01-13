<?php

namespace CommuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
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

        return $this->render('CommuBundle::index.html.twig', array(
          'tchat' => $tchat
        ));
    }
}
