<?php

namespace CoreBundle\Controller\_Blog\Blog;

use CoreBundle\Controller\_Blog\BlogController as Blog;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CoreBundle\Form\Type\ArticleType;
use CoreBundle\Form\Type\CommentaireType;
use CoreBundle\Entity\Article;
use CoreBundle\Entity\Image;

class MembreController extends Blog {

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/membre", name="membre_blog")
     */
      public function indexAction(Request $request)
      {
        $article = $this->get('core.blog')->index('MEMBRE');
        return $this->render('Blog/Membre/index.html.twig', array(
          'article' => $article
        ));
      }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/membre/admin", name="membre_admin")
     */
        public function adminAction(Request $request)
        {
            /* On récupère les articles via le service Blog */
            $article = $this->get('core.blog')->index('MEMBRE');
            return $this->render('Blog/Membre/admin.html.twig', array(
                'article' => $article
            ));
        }

    /**
     * @param Article $article
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/membre/article/{slug}", name="membre_view")
     */
      public function viewAction(Article $article, Request $request, $id)
      {
        $view = $this->getDoctrine()->getManager();
        $vue = $view->getRepository('BlogBundle:Article')->find($article);
          if(null === $vue){
              throw new NotFoundHttpException("L'article " . $id . "n'est pas disponible ou a été supprimé.");
          }
        /** On récupère les commentaires liés à l'article via l'article et on y joint les
        commentaires afin de pouvoir faire article->getCommentaires(), une fois effectuée,
        on affichera tout ceci via une boucle for dans la vue */
        $comm = $view->getRepository('BlogBundle:Commentaire')->findBy(array('article' => $vue));
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
        return $this->render('Blog/Membre/view.html.twig', array(
          'article' => $view,
          'commentaire' => $view,
          'form' => $view->createView()
        ));
      }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/membre/back/add", name="membre_add")
     */
      public function addAction(Request $request)
      {
        $form = $this->get('core.blog')->add($request, 'MEMBRE', 'membre_blog');
        return $this->render('Blog/Membre/add.html.twig', array(
          'form' => $form->createView()
        ));
      }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/membre/back/update/{id}", name="membre_update", requirements={"id": "\d+"})
     */
      public function updateAction(Request $request, $id)
      {
        $update = $this->get('corebundle.blog')->update($request, $id);
        return $this->render('Blog/Membre/update.html.twig', array(
          'form' => $update->createView(),
          'article' => $update
        ));
      }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/membre/back/delete/{id}", name="membre_delete", requirements={"id": "\d+"})
     */
      public function deleteAction(Request $request, $id)
      {
        $em = $this->delete($id);
        $this->addFlash('success', "L'article avec l'id " . $id . " a été supprimé");
        return $this->redirectToRoute('membre_admin');
      }
}
