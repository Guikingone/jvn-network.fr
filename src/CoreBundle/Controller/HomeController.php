<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{

    public function indexAction(Request $request)
    {
      $article = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->getArticle('TEAM', 'MEMBRE', 'KRMA');

      return $this->render('::home.html.twig', array(
        'article' => $article
      ));
    }

    public function contactAction()
    {
      return $this->render('::contact.html.twig');
    }

    public function mentionsAction()
    {
      return $this->render('::mentions.html.twig');
    }

    public function equipeAction()
    {
      return $this->render('::equipe.html.twig');
    }
}
