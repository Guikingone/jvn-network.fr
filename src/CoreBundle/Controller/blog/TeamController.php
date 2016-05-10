<?php
namespace CoreBundle\Controller\Blog;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use CoreBundle\Form\Type\ArticleType;
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
     * @Template("Blog\TEAM\admin.html.twig")
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
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/update/{id}", name="equipe_update", requirements={"id": "\d+"})
     */
      public function updateAction(Request $request, $id)
      {
        $um = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Article')->find($id);
        if (null === $um){
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $form = $this->createForm(ArticleType::class, $um);
        $form->handleRequest($request);
        if($form->isValid()){
          $um = $this->getDoctrine()->getManager();
          $um->flush();
          $this->addFlash('success', "L'annonce" . $id . "a bien été modifiée");
          return $this->redirectToRoute('team_admin');
        }
        return $this->render('Blog/Team/update.html.twig', array(
          'form' => $form->createView(),
          'article' => $um
        ));
      }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/back/delete/{id}", name="equipe_delete", requirements={"id": "\d+"})
     */
      public function deleteAction(Request $request, $id)
      {
        $this->get('core.back')->deleteArticle($id);
        $this->addFlash('success', "L'article avec l'id " . $id . " a été supprimé");
        return $this->redirectToRoute('equipe_admin');
      }
}
