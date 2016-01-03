<?php

namespace BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BoutiqueController extends Controller
{
    public function indexAction(Request $request)
    {
      $request->getSession()->getFlashBag()
              ->add('info', "La boutique n'est pas encore disponible, veuillez attendre les premières
              vagues d'invitations.");
        return $this->redirectToRoute('core_home');
    }

    public function panierAction()
    {
      
    }
}
