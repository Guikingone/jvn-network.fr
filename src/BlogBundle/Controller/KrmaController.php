<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Article;
use BlogBundle\Entity\Commentaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use BlogBundle\Form\Type\ArticleType;
use BlogBundle\Form\Type\ArticleEditType;
use BlogBundle\Form\Type\CommentaireType;
// Bien penser à HttpKernel pour afficher l'erreur d'ID

class KrmaController extends Controller{

  public function indexAction()
  {
    /** On récupère les articles via le repository Article et la fonction getArticleKrma, puis on retourne le tout
    dans la vue via une boucle for afin d'afficher les articles */
    $article = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('BlogBundle:Article')
                    ->getArticleKrma();
                    
    return $this->render('BlogBundle:Krma:index.html.twig', array(
      'article' => $article
    ));
  }

    public function viewAction(Article $article)
    {

    }

    public function adminAction()
    {
      /* On récupère les articles par catégories afin de les afficher via une boucle for dans le back office du blog,
      au besoin, on paginera le tout afin de fluidifier le résultat */
      $article = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('BlogBundle:Article')
                      ->getArticleKrma();

      return $this->render('BlogBundle:Krma:admin.html.twig', array(
        'article' => $article
      ));

    }

    public function addAction(Request $request)
    {
        /* On créer un nouvel article, on définit la date en fonction du jour
        afin de faciliter le travail de l'auteur, si besoin, il pourra la modifier via le formulaire, on ajoute aussi
        la catégorie afin de forcer l'affichage automatique */
        $art = new Article();
        $art->setDatePublication(new \Datetime);
        $art->setCategorie('KRMA');

        /* On appelle le formulaire depuis le namespace Form, on définit l'objet qui l'appelle puis on fait le lien
        requête <-> formulaire */
        $formbuilder = $this->createForm(ArticleType::class, $art);
        $formbuilder->handleRequest($request);

        /* On vérifie que les données sont valides, on appelle BigBrother qui écoutera les articles postés,
        on les persist, on enregistre le tout et on renvoit un message
        flash afin de valider l'enregistrement de l'article */
            if($formbuilder->isValid()){
              $em = $this->getDoctrine()->getManager();
              $em->persist($art);
              $em->flush();
              $request->getSession()->getFlashBag()->add('success', "Article enregistré");
            }
            return $this->render('BlogBundle:Krma:add.html.twig', array(
              'form' =>$formbuilder->createView()
            ));
    }

    public function updateAction()
    {

    }

    public function deleteAction()
    {
        /* On récupère l'entité via son ID, on fait appel à removeArticle qui effectue un ->delete()
        en fonction de l'ID, une fois effectué, on affiche un message d'info afin de valider la procédure
        et on redirige vers l'espace d'administration */

        $em = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('BlogBundle:Article')
                   ->removeArticle($id);

        $request->getSession()->getFlashBag()
                ->add('success', "L'article avec l'id " . $id . " a été supprimé");

        return $this->redirectToRoute('krma_admin');

    }
}
