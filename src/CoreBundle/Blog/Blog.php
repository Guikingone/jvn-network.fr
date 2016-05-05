<?php

namespace CoreBundle\Blog;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaire;
use BlogBundle\Form\Type\ArticleType;

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

  public function __construct(EntityManagerInterface $em, TokenStorage $user, Router $router)
  {
    $this->em = $em;
    $this->user = $user;
    $this->router = $router;
  }

  public function edit(Request $request, $id, $route)
  {
    /* On récupère l'entité via l'ID, si l'article n'existe pas, on renvoit un message d'erreur,
    on ouvre le formulaire, on valide, on affiche un message d'info afin
    de valider l'opération et on redirige vers la page d'administration.
    Ici, on se contente de vérifier que tout est valide, on ne persise pas car Doctrine connaît l'entité,
    une fois que tout est terminé, on affiche un message de succés et on redirige vers l'article en question */
    $update = $this->em->getRepository('BlogBundle:Article')->find($id);
    if(null === $update){
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    $formbuilder = $this->createForm(ArticleType::class, $update);
    $formbuilder->handleRequest($request);
    if($formbuilder->isValid()){
      $update = $this->em->flush();
      $request->getSession()->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée");
      return $this->redirectToRoute($route);
    }
    return $formbuilder;
  }

  public function view(Request $request, $id)
  {
    /* On va chercher l'article en fonction de son ID, si article inexistant, alors
    on retourne un message d'erreur 404, sinon, on affiche l'article puis les commentaires liés */
    $view = $this->em->getRepository('BlogBundle:Article')->find($id);
    $commentaire = $this->em->getRepository('BlogBundle:Commentaire')->findBy(array('article' => $view));

    $commentaire = new Commentaire();
    $commentaire->setdateCreation(new \Datetime);
    $commentaire->setArticle($article);
    $user = $this->user->getToken()->getUser();
    $commentaire->setAuteur($user);
    $formbuilder = $this->createForm(CommentaireType::class, $commentaire);
    $formbuilder->handleRequest($request);
    if($formbuilder->isValid()){
      $em = $this->em->persist($commentaire)->flush();
    }
    return $formbuilder;
  }
}
