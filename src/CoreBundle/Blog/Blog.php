<?php

namespace CoreBundle\Blog;

use Doctrine\ORM\EntityManagerInterface;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Blog {

  /**
  * @var EntityManagerInterface
  */
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function index($categorie)
  {
    /* On récupère les articles via le Repository Article afin de pouvoir appeler le service via le controller,
    ce dernier renverra le tout dans la vue via une boucle for */
    return $this->em->getRepository('BlogBundle:Article')->getArticle($categorie);
  }

  public function add(Request $request)
  {
  }

  public function update(Request $request, $id, $route)
  {
    /* On récupère l'article selon son ID (si il n'existe pas, on renvoit une erreur) puis on créer le formulaire
    de modification, on vérifie si la requête est valide puis on enregistre le tout, on envoie un message flash
    puis on redirige vers la route souhaitée */
    return $this->em->getRepository('BlogBundle:Article')->find($id);
    if(null === $id){
      throw new NotFoundHttpException("L'article " . $id . " n'est pas disponible ou a été supprimé.");
    }
    $form = $this->createForm(ArticleType::class, $um);
    $form->handleRequest($request);
    if($form->isValid()){
      $update = $this->getDoctrine()
                     ->getManager()
                     ->flush();
      $request->getSession()->getFlashBag()->add('success', "L'annonce " . $id . " a bien été modifiée");
      return $this->redirectToRoute($route);
    }
  }

  public function delete($id)
  {
    /* On récupère les articles à supprimer via leur id puis on supprime le tout */
    $purge = $this->em->getRepository('BlogBundle:Article')->find($id);
    $this->em->remove($purge);
    $this->em->flush();
  }

  public function view($article)
  {
  }
}
