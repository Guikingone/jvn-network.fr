<?php

namespace BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BoutiqueController extends Controller
{
    public function indexAction()
    {
        return $this->render('BoutiqueBundle::index.html.twig');
    }
}
