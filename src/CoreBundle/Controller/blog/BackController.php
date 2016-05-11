<?php

namespace CoreBundle\Controller\Blog;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CoreBundle\Entity\Article;

class BackController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/krma/admin", name="krma_admin")
     * @Template("Blog\Krma\admin.html.twig")
     */
    public function adminKrmaAction(Request $request)
    {
        $article = $this->get('core.back')->index('KRMA');
        $form = $this->get('core.back')->addArticle($request, 'KRMA');
        return array('article' => $article,'form' => $form->createView());
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

    /**
     * @param Request $request
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/equipe/admin/update/{id}", name="equipe_update", requirements={"id": "\d+"})
     * @Template("Blog\Membre\update.html.twig")
     */
    public function updateEquipeAction(Request $request, Article $article)
    {
        $form = $this->get('core.back')->updateArticle($request, $article);
        $this->redirectToRoute('equipe_admin');
        return array('form' => $form->createView(), 'article' => $article);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/krma/admin/update/{id}", name="krma_update", requirements={"id": "\d+"})
     * @Template("Blog\krma\update.html.twig")
     */
    public function updateKrmaAction(Request $request, Article $article)
    {
        $form = $this->get('core.back')->updateArticle($request, $article);
        $this->redirectToRoute('krma_admin');
        return array('form' => $form->createView(), 'article' => $article);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/membre/admin/update/{id}", name="membre_update", requirements={"id": "\d+"})
     * @Template("Blog\Membre\update.html.twig")
     */
    public function updateMembreAction(Request $request, Article $article)
    {
        $form = $this->get('core.back')->updateArticle($request, $article);
        $this->redirectToRoute('membre_admin');
        return array('form' => $form->createView(), 'article' => $article);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/equipe/admin/delete/{id}", name="equipe_delete", requirements={"id": "\d+"})
     */
    public function deleteEquipeAction($id)
    {
        $this->get('core.back')->deleteArticle($id);
        $this->addFlash('success', "L'article a été supprimé");
        return $this->redirectToRoute('equipe_admin');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/krma/admin/delete/{id}", name="krma_delete", requirements={"id": "\d+"})
     */
    public function deleteKrmaAction($id)
    {
        $this->get('core.back')->deleteArticle($id);
        return $this->redirectToRoute('krma_admin');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/membre/admin/delete/{id}", name="membre_delete", requirements={"id": "\d+"})
     */
    public function deleteMembreAction($id)
    {
        $this->get('core.back')->deleteArticle($id);
        $this->addFlash('success', "L'article a été supprimé");
        return $this->redirectToRoute('membre_admin');
    }
}
