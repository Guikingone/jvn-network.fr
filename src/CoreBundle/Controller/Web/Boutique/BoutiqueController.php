<?php

namespace CoreBundle\Controller\Web\Boutique;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BoutiqueController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/boutique", name="boutique")
     * @Template("Boutique/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(){}
}
