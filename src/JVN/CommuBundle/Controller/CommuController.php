<?php

namespace JVN\CommuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommuController extends Controller
{
    public function indexAction()
    {
        return $this->render('CommuBundle:Commu:forums.html.twig');
    }
}
