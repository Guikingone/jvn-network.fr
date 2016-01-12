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

    /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
    requête <-> formulaire */
    $formbuilder = $em->createForm(ArticleType::class, $article);
    $formbuilder->handleRequest($request);

        /* On vérifie que les données sont valides, on les persist, on enregistre le tout et on renvoit un message
        flash afin de valider l'enregistrement de l'article */
        if($formbuilder->isValid()){
           $em = $this->getDoctrine()->getManager();
           $em->persist($article);
           $em->flush();
           $request->getSession()->getFlashBag()->add('success', "Article enregistré");
         }
  }

  public function update(Request $request)
  {

  }

  public function delete()
  {

  }

  public function view()
  {

  }
}
