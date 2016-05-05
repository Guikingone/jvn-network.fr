<?php

namespace CoreBundle\Controller\_Blog\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CoreBundle\Form\Type\ArticleType;
use CoreBundle\Form\Type\CommentaireType;
use CoreBundle\Entity\Article;
use CoreBundle\Entity\Commentaire;
use CoreBundle\Entity\Image;

class KrmaController extends Controller{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma", name="krma")
     */
    public function indexAction()
    {
      $article = $this->get('core.blog')->index('KRMA');
      return $this->render('Blog/Krma/index.html.twig', array(
        'article' => $article
      ));
    }

    /**
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma/article/{id}/{slug}", name="krma_view", requirements={"id": "\d+"})
     */
    public function viewAction(Article $article, Request $request)
    {
      /* On va chercher l'article en fonction de son ID, si article inexistant, alors
      on retourne un message d'erreur 404, sinon, on affiche l'article puis les commentaires */
      $view = $this->getDoctrine()->getManager();
      $vue = $view->getRepository('BlogBundle:Article')->find($article);
      $comm = $view->getRepository('BlogBundle:Commentaire')->findBy(array('article' => $vue));
      /** On récupère les commentaires liés à l'article via l'article et on y joint les
      commentaires afin de pouvoir faire article->getCommentaires(), une fois effectuée,
      on affichera tout ceci via une boucle for dans la vue */
      $commentaire = new Commentaire();
      $commentaire->setdateCreation(new \Datetime);
      $commentaire->setArticle($article);
      $user = $this->getUser();
      $commentaire->setAuteur($user);
      $formCommentaire = $this->createForm(CommentaireType::class, $commentaire);
      $formCommentaire->handleRequest($request);
      if($formCommentaire->isValid()){
        $em = $this->getDoctrine()->getManager();
        $em->persist($commentaire);
        $em->flush();
      }
      return $this->render('Blog/Krma/view.html.twig', array(
        'article' => $vue,
        'commentaire' => $commentaire,
        'form' => $formCommentaire->createView()
      ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma/admin", name="krma_admin")
     */
    public function adminAction(Request $request)
    {
        $article = $this->get('core.blog')->index('KRMA');
        $form = $this->get('core.blog')->add($request, 'KRMA', 'krma_admin');
        return $this->render('Blog/Krma/admin.html.twig', array(
            'article' => $article,
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/krma/add", name="krma_add")
     */
    public function addAction(Request $request)
    {
        $form = $this->get('core.blog')->add($request, 'KRMA', 'krma');
        return $this->render('Blog/Krma/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/krma/back/update/{id}", name="krma_update", requirements={"id": "\d+"})
     */
        public function updateAction(Request $request, $id)
        {
          /* On récupère l'entité via l'ID, si l'article n'existe pas, on renvoit un message d'erreur,
          on ouvre le formulaire, on valide, on affiche un message d'info afin
          de valider l'opération et on redirige vers la page d'administration */
          $update = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Article')->find($id);
          if(null === $update){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
          }
          $form = $this->createForm(ArticleType::class, $update);
          $form->handleRequest($request);

          /* Ici, on se contente de vérifier que tout est valide, on ne persise pas car Doctrine connaît l'entité,
          une fois que tout est terminé, on affiche un message de succés et on redirige vers l'article en question */
          if($form->isValid()){
              $update = $this->getDoctrine()->getManager();
              $update->flush();
              $request->getSession()->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée.");
              return $this->redirectToRoute('krma_admin');
          }
          return $this->render('Blog/Krma/update.html.twig', array(
            'form' => $form->createView(),
            'article' => $update
          ));
        }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/krma_delete/{id}", name="krma_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {
      $em = $this->get('core.blog')->delete($id);
      $this->addFlash('success', "L'article avec l'id " . $id . " a été supprimé");
      return $this->redirectToRoute('krma_admin');
    }
}
