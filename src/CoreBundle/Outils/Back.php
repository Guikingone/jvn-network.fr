<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 04/05/2016
 * Time: 18:08
 *
 * Back Service is used to control the entire back part of the application, he's also used by the Controller in order
 * to be able to add, delete, update and view a entity without passing by the Main Controller.
 */

namespace CoreBundle\Outils;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use CoreBundle\Entity\Article;
use CoreBundle\Entity\Commentaire;
use CoreBundle\Form\Type\ArticleType;
use CoreBundle\Form\Type\CommentaireType;

use CoreBundle\Entity\Sujet;
use CoreBundle\Entity\Message;
use CoreBundle\Form\Type\SujetType;
use CoreBundle\Form\Type\MessageType;

class Back
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
     * Blog constructor.
     * @param EntityManager $doctrine
     * @param Session $session
     * @param Router $router
     * @param TokenStorage $user
     * @param FormFactory $formbuilder
     */
    public function __construct(EntityManager $doctrine, Session $session, Router $router, TokenStorage $user, FormFactory $formbuilder)
    {
        $this->doctrine = $doctrine;
        $this->session = $session;
        $this->router = $router;
        $this->user = $user;
        $this->formbuilder = $formbuilder;
    }

    /**
     * @param $string
     * @return mixed
     *
     * Allow to slugify the titre of a article or a subject
     */
    public function slugify($string)
    {
        return preg_replace('/[^a-z0-9]/', '-', strtolower(trim(strip_tags($string))));
    }

    /**
     *
     * Here is the back function used by the application, every function is used by the main controller in order to
     * gain time and performance.
     *
     */

    /**
     * @param $categorie
     * @return array
     *
     * Allow the user to show every article persisted, the $categorie depend on the param passed
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
     * ArticleType and submit the Form, the Slug service is used to change the format of the article titre, if
     * everything is matched, the service persist the entity and save a flash message in the session.
     */
    public function addArticle(Request $request, $categorie)
    {
        $article = new Article();
        $article->setDatePublication(new \Datetime);
        $article->setCategorie($categorie);
        $user = $this->user->getToken()->getUser();
        $article->setAuteur($user);

        $form = $this->formbuilder->create(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isValid()){
            $slug = $this->slugify($article->getTitre());
            $article->setSlug($slug);
            $this->doctrine->persist($article);
            $this->doctrine->flush();
            $this->session->getFlashBag()->add('success', "Article enregistré");
        }
        return $form;
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     *
     * Allow the user to view the article using the repository to find the article, create a form for submitting the
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

    /**
     * @param Request $request
     * @param $id
     * @return $form
     *
     * Allow the user to update the article by searching with the $id, the form in relation is create and submitted
     * only if everything is validated, a flash message is created and stocked in the session.
     */
    public function updateArticle(Request $request, $id)
    {
        $update = $this->doctrine->getRepository('CoreBundle:Article')->find($id);
        if(null === $update){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }
        $formUpdate = $this->formbuilder->create(ArticleType::class, $update);
        $formUpdate->handleRequest($request);
        if($formUpdate->isValid()){
            $this->doctrine->flush();
            $this->session->getFlashBag()->add('success', "L'annonce" . $id . "a bien été modifiée.");
        }
        return $formUpdate;
    }

    /**
     * @param $id
     *
     * Allow to delete a article by is $id, a flash message is generate to confirm this action
     */
    public function deleteArticle($id)
    {
        $purge = $this->doctrine->getRepository('CoreBundle:Article')->find($id);
        $this->doctrine->remove($purge);
        $this->doctrine->flush();
        $this->session->getFlashBag()->add('success', "L'article avec l'id" . $id . " a bien été supprimé");
    }

    /**
     * @param $id
     *
     * Allow to delete a commentary by is $id
     */
    public function deleteCommentaire($id)
    {
        $purge = $this->doctrine->getRepository('CoreBundle:Commentaire')->find($id);
        $this->doctrine->remove($purge);
        $this->doctrine->flush();
        $this->session->getFlashBag()->add('success', "Le commentaire avec l'id" . $id . " a bien été supprimé");
    }

    /*
     * This service is used to control the Forums/Community aspect, the methods declared below this line are shared
     * through the entire application via the dependency injection.
     */

    /**
     * @param $categorie
     * @return array
     *
     * Allow to show the subject depending on the categories passed by the controller.
     */
    public function indexForums($categorie)
    {
        return $this->doctrine->getRepository('CoreBundle:Sujet')->getSujet($categorie);
    }

    /**
     * @param Request $request
     * @param $categorie
     *
     * Allow to add a subject by passing via modal windows, the method set the author, the slug and the $categorie,
     * if everything is in place, the service call doctrine and persist the subject.
     */
    public function addSujet(Request $request, $categorie)
    {
        $sujet = new Sujet();
        $sujet->setDateCreation(new \Datetime);
        $sujet->setCategory($categorie);
        $user = $this->user->getToken()->getUser();
        $sujet->setAuteur($user);
        $form = $this->formbuilder->create(SujetType::class, $sujet);
        $form->handleRequest($request);
        if($form->isValid()){
            $slug = $this->slugify($sujet->getTitre());
            $sujet->setSlug($slug);
            $this->doctrine->persist($sujet);
            $this->doctrine->flush();
        }
        return $form;
    }

    public function updateSujet()
    {
        
    }

    /**
     * @param $id
     *
     * Allow to delete a subject using is $id
     */
    public function deleteSujet($id)
    {
        $purge = $this->doctrine->getRepository('CoreBundle:Sujet')->find($id);
        $this->doctrine->remove($purge);
        $this->doctrine->flush();
        $this->session->getFlashBag()->add('success', "Le sujet a bien été supprimé !");
    }

    /**
     * @param $id
     *
     * Allow to delete a message using is $id
     */
    public function deleteMessage($id)
    {
        $purge = $this->doctrine->getRepository('CoreBundle:Message')->find($id);
        $this->doctrine->remove($purge);
        $this->doctrine->flush();
        $this->session->getFlashBag()->add('success', "Le message a bien été supprimé !");
    }
}