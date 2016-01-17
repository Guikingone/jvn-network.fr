<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller{

    public function indexAction(Request $request)
    {
      $article = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('BlogBundle:Article')
                      ->getArticle('TEAM', 'MEMBRE', 'KRMA');

      return $this->render('CoreBundle::home.html.twig', array(
        'article' => $article
      ));
    }

    public function contactAction()
    {
      return $this->render('CoreBundle::contact.html.twig');
    }

    public function mentionsAction()
    {
      return $this->render('CoreBundle::mentions.html.twig');
    }

    public function equipeAction()
    {
      return $this->render('CoreBundle::equipe.html.twig');
    }
}
