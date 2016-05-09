<?php
namespace CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home")
     * @Template("basics\home.html.twig")
     */
    public function indexAction(Request $request)
    {
      $article = $this->get('core.back')->index('MEMBRE');
      return array( 'article' => $article );
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
     * @Route("/propos", name="propos")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function proposAction()
    {
      return $this->render('basics/equipe.html.twig');
    }
}
