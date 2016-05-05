<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 04/05/2016
 * Time: 18:08
 *
 * Blog Service is used to create a instance of a blog in the application, he's also used by the Controller in order to
 * be able to add, delete, update and view a entity without passing by the Main Controller.
 */

namespace CoreBundle\Outils;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use CoreBundle\Entity\Article;
use CoreBundle\Entity\Commentaire;
use CoreBundle\Form\Type\ArticleType;
use CoreBundle\Form\Type\CommentaireType;

class Blog
{
    /**
     * @var EntityManager
     */
    protected $doctrine;

    /**
     * @var TokenStorage
     */
    protected $user;

    /**
     * @var FormFactory
     */
    protected $formbuilder;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Slug
     */
    protected $slug;

    /**
     * Blog constructor.
     * @param EntityManager $doctrine
     * @param TokenStorage $user
     */
    public function __construct(EntityManager $doctrine, Session $session, Router $router, TokenStorage $user, FormFactory $formbuilder, Slug $slug)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
        $this->router = $router;
        $this->user = $user;
        $this->formbuilder = $formbuilder;
        $this->slug = $slug;
    }

    /**
     * @param $categorie
     * @return array
     *
     * Allow the user to show every article persisted with doctrine, the $categorie depend on the param passed
     * from the controller.
     */
    public function index($categorie)
    {
        return $this->doctrine->getRepository('CoreBundle:Article')->getArticle($categorie);
    }

    /**
     * @param Request $request
     * @param $categorie
     * @return mixed
     *
     * Allow the user to add a article by passing the back office, the service create a Form using the
     * ArticleType and
     */
    public function add(Request $request, $categorie, $route)
    {
        $article = new Article();
        $article->setDatePublication(new \Datetime);
        $article->setCategorie($categorie);
        $user = $this->user->getToken()->getUser();
        $article->setAuteur($user);

        $form = $this->formbuilder->create(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isValid()){
            $this->slug->slugify($article->getTitre());
            $article->setSlug($this->slug);
            $this->doctrine->persist($article);
            $this->doctrine->flush();
            $this->session->getFlashBag()->add('success', "Article enregistré");
            return $this->router->generate($route);
        }
        return $form;
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     *
     * Alow the user to view the article using the repository to find the article, create a form for submitting the
     * comments linked to the article.
     */
    public function viewArticle(Request $request, $id)
    {
        $view = $this->doctrine->getRepository('CoreBundle:Article')->find($id);
        $comments = $this->doctrine->getRepository('CoreBundle:Commentaire')->findBy(array('article' => $view));

        $comments = new Commentaire();
        $comments->setdateCreation(new \Datetime);
        $comments->setArticle($view);
        $user = $this->user->getToken()->getUser();
        $comments->setAuteur($user);
        $formComments = $this->formbuilder->create(CommentaireType::class, $comments);
        $formComments->handleRequest($request);
        if($formComments->isValid()){
            $this->doctrine->persist($comments);
            $this->doctrine->flush();
        }
        return $formComments;
    }

    public function updateArticle()
    {

    }

    /**
     * @param $id
     *
     * Allow to delete a article by is $id
     */
    public function deleteArticle($id)
    {
        $purge = $this->doctrine->getRepository('BlogBundle:Article')->find($id);
        $this->doctrine->remove($purge);
        $this->doctrine->flush();
    }
}