<?php

namespace CoreBundle\Blog;

use Doctrine\ORM\EntityManagerInterface;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;

class Blog {

  /**
  * @var EntityManagerInterface
  */
  private $em;

  protected $repository;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function add(Request $request, $categorie)
  {
    /* On créer un nouvel article, on définit la date en fonction du jour
    afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire */
    $article = new article();
    $article->setDatePublication(new \Datetime);
    $article->setCategorie($categorie);
  }

  public function update(Request $request)
  {

  }

  public function delete()
  {

  }

  public function view()
  {
    /* On va chercher l'article en fonction de son ID, si article inexistant, alors
    on retourne un message d'erreur 404, sinon, on affiche l'article puis les commentaires liés */

    $view = $this->getDoctrine()->getManager();
    $vue = $view
        ->getRepository($repository)
        ->find($article);
  }
}
