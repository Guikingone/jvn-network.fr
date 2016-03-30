<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
      $article = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->getArticle('TEAM', 'MEMBRE', 'KRMA');

      return $this->render('basics/home.html.twig', array(
        'article' => $article
      ));
    }

    /**
     * @Route("/contact", name="contact")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction()
    {
      return $this->render('basics/contact.html.twig');
    }

    /**
     * @Route("/mentions", name="mentions")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mentionsAction()
    {
      return $this->render('basics/mentions.html.twig');
    }

    /**
     * @Route("/equipe", name="equipe")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function equipeAction()
    {
      return $this->render('basics/equipe.html.twig');
    }
}
