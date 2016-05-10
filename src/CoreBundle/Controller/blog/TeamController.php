<?php
namespace CoreBundle\Controller\Blog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use CoreBundle\Entity\Article;
use CoreBundle\Entity\Commentaire;

/**
 * @Route("/equipe")
 */
class TeamController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="equipe")
     * @Template("Blog\Team\index.html.twig")
     */
      public function indexAction()
      {
        $article = $this->get('core.back')->index('TEAM');
        return array('article' => $article);
      }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin", name="equipe_admin")
     * @Template("Blog\Team\admin.html.twig")
     */
    public function adminAction(Request $request)
    {
        $article = $this->get('core.back')->index('TEAM');
        $form = $this->get('core.back')->addArticle($request, 'TEAM');
        $membre = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->getUser();
        $commentaire = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Commentaire')->getCommentaires();
        return array(
            'article' => $article,
            'form' => $form->createView(),
            'membre' => $membre,
            'commentaire' => $commentaire
        );
    }

    /**
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/article/{slug}", name="equipe_article")
     * @Template("Blog\Team\view.html.twig")
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
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/update/{id}", name="equipe_update", requirements={"id": "\d+"})
     * @Template("Blog\Membre\update.html.twig")
     */
      public function updateAction(Request $request, Article $article)
      {
          $form = $this->get('core.back')->updateArticle($request, $article);
            return array('form' => $form->createView(), 'article' => $article);
      }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/back/delete/{id}", name="equipe_delete", requirements={"id": "\d+"})
     */
      public function deleteAction($id)
      {
        $this->get('core.back')->deleteArticle($id);
        $this->addFlash('success', "L'article a été supprimé");
        return $this->redirectToRoute('equipe_admin');
      }
}
