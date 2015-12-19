<?php

namespace KrmaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction(){
        return $this->render('KrmaBundle::home.html.twig');
    }
}
