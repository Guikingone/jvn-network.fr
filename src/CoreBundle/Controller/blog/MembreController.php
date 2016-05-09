<?php

namespace CoreBundle\Controller\Blog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use CoreBundle\Form\Type\ArticleType;
use CoreBundle\Form\Type\CommentaireType;
use CoreBundle\Entity\Article;

/**
 * @Route("/membre")
 */
class MembreController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="membre_blog")
     * @Template("Blog\Membre\index.html.twig")
     */
      public function indexAction()
      {
        $article = $this->get('core.back')->index('MEMBRE');
        return array('article' => $article);
      }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin", name="membre_admin")
     * @Template("Blog\Membre\admin.html.twig")
     */
        public function adminAction(Request $request)
        {
            $article = $this->get('core.back')->index('MEMBRE');
            $form = $this->get('core.back')->addArticle($request, 'MEMBRE');
            return array('article' => $article, 'form' => $form->createView());
        }

    /**
     * @param Article $article
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/article/{slug}", name="membre_view")
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
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/update/{id}", name="membre_update", requirements={"id": "\d+"})
     */
      public function updateAction(Request $request, $id)
      {
        $update = $this->get('core.back')->update($request, $id);
        return $this->render('Blog/Membre/update.html.twig', array(
          'form' => $update->createView(),
          'article' => $update
        ));
      }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/delete/{id}", name="membre_delete", requirements={"id": "\d+"})
     */
      public function deleteAction(Request $request, $id)
      {
        $this->get('core.back')->deleteArticle($id);
        $this->addFlash('success', "L'article avec l'id " . $id . " a été supprimé");
        return $this->redirectToRoute('membre_admin');
      }
}
