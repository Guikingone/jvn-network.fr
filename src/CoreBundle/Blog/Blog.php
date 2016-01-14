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
    return $this->em->getRepository('BlogBundle:Article')
                    ->getArticleBlog($categorie);
  }

  public function add(Request $request, $categorie)
  {
    /* On créer un nouvel article, on définit la date en fonction du jour
    afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire */
    $article = new article();
    $article->setDatePublication(new \Datetime);
    $article->setCategorie($categorie);
  }

  public function update(Request $request, $id, $route)
  {
    /* On récupère l'entité via l'ID, si l'article n'existe pas, on renvoit un message d'erreur,
    on ouvre le formulaire, on valide, on affiche un message d'info afin
    de valider l'opération et on redirige vers la page d'administration */
    $update = $this->em->getRepository('BlogBundle:Article')
                        ->find($id);
    if(null === $update){
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    $form = $this->createForm(ArticleType::class, $update);
    $form->handleRequest($request);

    /* Ici, on se contente de vérifier que tout est valide, on ne persise pas car Doctrine connaît l'entité,
    une fois que tout est terminé, on affiche un message de succés et on redirige vers l'article en question */
    if($form->isValid()){
       $update = $this->em->flush();
       $request->getSession()->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée");
       return $this->redirectToRoute($route);
    }
  }

  public function delete($id)
  {
    /* On récupère les articles à supprimer via leur id puis on supprime le tout */
    $purge = $this->em->getRepository('BlogBundle:Article')
                      ->find($id);
    $this->em->remove($purge);
    $this->em->flush();
  }

  public function view()
  {

  }
}
