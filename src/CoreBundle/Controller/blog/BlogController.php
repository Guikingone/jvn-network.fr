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
    public function KrmaAction()
    {
      $article = $this->get('core.back')->index('KRMA');
      return array('article' => $article);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/membre", name="membre_blog")
     * @Template("Blog\Membre\index.html.twig")
     */
    public function MembreAction()
    {
        $article = $this->get('core.back')->index('MEMBRE');
        return array('article' => $article);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/equipe", name="equipe")
     * @Template("Blog\Team\index.html.twig")
     */
    public function EquipeAction()
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
}
