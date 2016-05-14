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
     * @Route("/admin/lock/{id}", name="back_lock", requirements={"id": "\d+"})
     */
    public function lockArticleAction(Request $request, $id)
    {
        $this->get('core.back')->lockArticle($id);
        $referer = $request->headers->get('referer');
        $router = $this->get('router');
        $route = $router->match($referer);
        return $this->redirectToRoute($route);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/unlock/{id}", name="back_unlock", requirements={"id": "\d+"})
     */
    public function unlockArticleAction(Request $request, $id)
    {
        $this->get('core.back')->unlockArticle($id);
        $referer = $request->headers->get('referer');
        $router = $this->get('router');
        $route = $router->match('referer');
        return $this->redirectToRoute($route);
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
        $this->addFlash('success', "L'article a été supprimé");
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
