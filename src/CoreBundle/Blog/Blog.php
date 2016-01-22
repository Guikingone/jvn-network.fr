<?php

namespace CoreBundle\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Article;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BlogBundle\Form\Type\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Formfactory;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class Blog extends Controller {

  /**
  * @var EntityManagerInterface
  */
  private $em;

  /**
  * @var FormFactory
  */
  private $formbuilder;

  /**
  * @var TokenStorage
  */
  private $user;

  public function __construct(EntityManagerInterface $em, FormFactory $formbuilder, TokenStorage $user)
  {
    $this->em = $em;
    $this->formbuilder = $formbuilder;
    $this->user = $user;
  }

  public function index($categorie)
  {
    return $this->em->getRepository('BlogBundle:Article')->getArticle($categorie);
  }

  public function add(Request $request, $categorie, $route)
  {
    /* On créer un nouvel article, on définit la date en fonction du jour
    afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire, on ajoute aussi
    la catégorie afin de forcer l'affichage automatique */
    $article = new Article();
    $article->setDatePublication(new \Datetime);
    $article->setCategorie($categorie);
    $user = $this->user->getToken()->getUser();
    $article->setAuteur($article);

    /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
    requête <-> formulaire */
    $form = $this->createForm(ArticleType::class, $article);
    $form->handleRequest($request);

    /* On vérifie que les données sont valides, on les persist, on enregistre le tout et on renvoit un message
    flash afin de valider l'enregistrement de l'article */
    if($form->isValid()){
      $em = $this->em->persist($article);
      $em = $this->em->flush();
      $request->getSession()->getFlashBag()->add('success', "Article enregistré");
      return $this->redirectToRoute($route);
    }
    return $form;
  }

  public function update()
  {
  }

  public function delete($id)
  {
    /* On récupère les articles à supprimer via leur id puis on supprime le tout */
    $purge = $this->em->getRepository('BlogBundle:Article')->find($id);
    $this->em->remove($purge);
    $this->em->flush();
  }
}
