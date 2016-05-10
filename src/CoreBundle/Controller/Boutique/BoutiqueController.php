<?php

namespace CoreBundle\Controller\Boutique;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BoutiqueController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/boutique", name="boutique")
     */
    public function indexAction()
    {
        return $this->render('Boutique/index.html.twig');
    }
}
