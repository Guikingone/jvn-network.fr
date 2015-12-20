<?php

namespace ForumsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ForumsController extends Controller
{
    public function indexAction()
    {
        return $this->render('ForumsBundle::index.html.twig');
    }
}
