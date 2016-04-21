<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BoutiqueController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/boutique", name="boutique")
     */
    public function indexAction(Request $request)
    {
        return $this->render('Boutique/index.html.twig');
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panierAction()
    {
    }
}
