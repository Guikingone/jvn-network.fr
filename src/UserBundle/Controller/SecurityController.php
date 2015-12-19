<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction(Request $request){
    // Si le visiteur est identifié, redirection vers home
    if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
      return $this->redirectToRoute('core_home');
     }

     $authenticationUtils = $this->get('security.authentication_utils');
     return $this->render('UserBundle:Security:login.html.twig', array(
       'last_username' => $authenticationUtils->getlastUsername(),
       'error'         => $authenticationUtils->getLastAuthenticationError(),
     ));
    }
}
