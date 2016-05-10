<?php

namespace CoreBundle\Controller\Blog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use CoreBundle\Form\Type\ArticleType;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/article/{slug}", name="membre_view")
     * @Template("Blog\Membre\view.html.twig")
     */
      public function viewAction(Article $article, Request $request)
      {
          $form = $this->get('core.back')->viewArticle($request, $article);
          $commentaire = $this->getDoctrine()
                              ->getManager()
                              ->getRepository('CoreBundle:Commentaire')
                              ->findBy(array('article' => $article));
          return array('article' => $article, 'commentaire' => $commentaire, 'form' => $form->createView());
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
