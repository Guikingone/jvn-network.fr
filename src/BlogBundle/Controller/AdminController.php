<?php
namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller{

  public function adminAction(Request $request)
  {
    // On crée un nouvel article
    $art = new Article();
    $art->setTitre('Bonjour à tous !');
    $art->setAuteur('Guikingone');
    $art->setContenu('Bienvenue sur JVN-Network.fr, le site est tout neuf, bien entendu, je vous demande un peu de bon sens
  et de compassion, ce n\'est qu\'un début');
    $art->setCategorie('Annonce');
    $art->setDatePublication(new \Datetime);

    $em = $this->getDoctrine()->getManager();
    $em->persist($art);
    $em->flush();

    return $this->render('BlogBundle::admin.html.twig');
  }
}
