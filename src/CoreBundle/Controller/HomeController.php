<?php
namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller{

    public function indexAction($page){
      if($page < 1){
        throw new NotFoundHttpException("La page . $page . est inexistante");
      }
      return $this->render('::layout.html.twig');
    }
}
