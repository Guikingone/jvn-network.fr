<?php

namespace CoreBundle\Controller\Web\Communaute;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommunauteController extends Controller
{
    /**
     * @Route("/communaute", name="communaute")
     * @Template("Communaute/index.html.twig")
     * @Method("GET")
     */
    public function indexAction()
    {
    }
}
