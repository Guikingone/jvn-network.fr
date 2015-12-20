<?php

namespace CommuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommuController extends Controller
{
    public function indexAction()
    {
        return $this->render('CommuBundle::index.html.twig');
    }
}
