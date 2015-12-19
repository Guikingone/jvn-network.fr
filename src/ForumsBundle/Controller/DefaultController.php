<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ForumsBundle:Default:index.html.twig');
    }
}
