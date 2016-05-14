<?php

namespace CoreBundle\Controller\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CoreBundle\Entity\Article;

class BlogController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma", name="krma")
     * @Template("Blog\Krma\index.html.twig")
     */
    public function krmaAction()
    {
      $article = $this->get('core.back')->index('KRMA');
      return array('article' => $article);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/membre", name="membre_blog")
     * @Template("Blog\Membre\index.html.twig")
     */
    public function membreAction()
    {
        $article = $this->get('core.back')->index('MEMBRE');
        return array('article' => $article);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/equipe", name="equipe")
     * @Template("Blog\Team\index.html.twig")
     */
    public function equipeAction()
    {
        $article = $this->get('core.back')->index('TEAM');
        return array('article' => $article);
    }

    /**
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/article/{id}/{slug}", name="home_view", requirements={"id": "\d+"})
     * @Template("Blog\view.html.twig")
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma/admin", name="krma_admin")
     * @Template("Blog\Krma\admin.html.twig")
     */
    public function adminKrmaAction(Request $request)
    {
        $article = $this->get('core.back')->index('KRMA');
        $form = $this->get('core.back')->addArticle($request, 'KRMA');
        return array('article' => $article, 'form' => $form->createView());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/membre/admin", name="membre_admin")
     * @Template("Blog\Membre\admin.html.twig")
     */
    public function adminMembreAction(Request $request)
    {
        $article = $this->get('core.back')->index('MEMBRE');
        $form = $this->get('core.back')->addArticle($request, 'MEMBRE');
        return array('article' => $article, 'form' => $form->createView());
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/equipe/admin", name="equipe_admin")
     * @Template("Blog\Team\admin.html.twig")
     */
    public function adminEquipeAction(Request $request)
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
}
