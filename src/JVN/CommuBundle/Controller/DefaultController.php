<?php

namespace JVN\CommuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CommuBundle:Default:index.html.twig');
    }
}
