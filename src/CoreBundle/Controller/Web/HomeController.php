<?php

namespace CoreBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home")
     * @Template("basics\home.html.twig")
     * @Method("GET")
     */
    public function indexAction()
    {
        $article = $this->get('core.back')->showArticles('KRMA', 'MEMBRE', 'TEAM');

        return array('article' => $article);
    }

    /**
     * @Route("/contact", name="contact")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     */
    public function contactAction()
    {
        return $this->render('basics/contact.html.twig');
    }

    /**
     * @Route("/mentions", name="mentions")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     */
    public function mentionsAction()
    {
        return $this->render('basics/mentions.html.twig');
    }

    /**
     * @Route("/propos", name="propos")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     */
    public function proposAction()
    {
        return $this->render('basics/equipe.html.twig');
    }
}
