<?php

namespace CoreBundle\Blog;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaire;

class Blog {

  /**
  * @var EntityManagerInterface
  */
  private $em;

  /**
  * @var FormFactory
  */
  private $form;

  public function __construct(EntityManagerInterface $em, FormFactory $form)
  {
    $this->em = $em;
    $this->form = $form;
  }

  public function index($categorie)
  {
    /* On récupère les articles via le Repository Article afin de pouvoir appeler le service via le controller,
    ce dernier renverra le tout dans la vue via une boucle for */
    return $this->em->getRepository('BlogBundle:Article')->getArticle($categorie);
  }

  public function view(Request $request, $article, $commentaire, $user)
  {
    /* On récupère l'article via son ID, on l'affiche en offrant la possibilité de commenter l'article, on initialise
    l'utilisateur afin de lier le commentaire à un compte User ainsi que la date du commentaire (pour plus de
    stabilitée et de cohérence dans la BDD), au besoin,
    on vérifiera le contenu du commentaire via un service BigBrother */
    $article = $this->em->getRepository('BlogBundle:Article')->find($id);
    $commentaire = $this->em->getRepository('BlogBundle:Commentaire')->findBy(array('article' => $article));
    $user = $this->getUser();

    $commentaire = new Commentaire();
    $commentaire->setDateCreation(new \Datetime);
    $commentaire->setAuteur($user);
    $form_Commentaire = $this->createForm(CommentaireType::class, $commentaire);
    $form_Commentaire->handleRequest($request);
      if($formCommentaire->isValid()){
        $em = $this->getDoctrine()->getManager()->persist($commentaire)->flush();
      }
  }

  public function add(Request $request)
  {
  }

  public function update(Request $request, $id, $route, $render, $form)
  {
    /* On récupère l'article selon son ID (si il n'existe pas, on renvoit une erreur) puis on créer le formulaire
    de modification, on vérifie si la requête est valide puis on enregistre le tout, on envoie un message flash
    de succés puis on redirige vers la route souhaitée */
    return $this->em->getRepository('BlogBundle:Article')->find($id);
    if(null === $id){
      throw new NotFoundHttpException("L'article " . $id . " n'est pas disponible ou a été supprimé.");
    }
    $form = $this->createForm(ArticleType::class);
    $form->handleRequest($request);
      if($form->isValid()){
        $update = $this->getDoctrine()->getManager()->flush();
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
}
