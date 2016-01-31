<?php

namespace CoreBundle\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Article;
use BlogBundle\Form\Type\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Blog extends Controller {

  /**
  * @var EntityManagerInterface
  */
  protected $em;

  protected $formbuilder;

  /**
  * @var TokenStorage
  */
  protected $user;

  /**
  * @var Router
  */
  protected $router;

  public function __construct(EntityManagerInterface $em, $formbuilder, TokenStorage $user, Router $router)
  {
    $this->em = $em;
    $this->formbuilder = $formbuilder;
    $this->user = $user;
    $this->router = $router;
  }

  /* Ces méthodes sont réécrites ici afin de faciliter la prise en charge des envoies d'article */
  public function createForm($type, $data = null, array $options = array())
  {
    return $this->formbuilder->create($type, $data, $options);
  }

  public function redirectToRoute($route, array $parameters = array(), $status = 302)
  {
    return $this->redirect($this->generateUrl($route, $parameters), $status);
  }

  protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
  {
    return $this->container->get('router')->generate($route, $parameters, $referenceType);
  }

  /* Méthode propre à l'envoi des articles et à leur traitement */
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
    $article->setAuteur($user);

    /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
    requête <-> formulaire */
    $formbuilder = $this->createForm(ArticleType::class, $article);
    $formbuilder->handleRequest($request);

    /* On vérifie que les données sont valides, on les persist, on enregistre le tout et on renvoit un message
    flash afin de valider l'enregistrement de l'article, on renvoie $formbuilder afin que la vue puisse
    afficher le formulaire */
    if($formbuilder->isValid()){
      $em = $this->em->persist($article);
      $em = $this->em->flush();
      $request->getSession()->getFlashBag()->add('success', "Article enregistré");
    }
    return $formbuilder;
  }

  public function delete($id)
  {
    /* On récupère les articles à supprimer via leur id puis on supprime le tout */
    $purge = $this->em->getRepository('BlogBundle:Article')->find($id);
    $this->em->remove($purge);
    $this->em->flush();
  }
}
