<?php

namespace CoreBundle\Blog;

use Doctrine\ORM\EntityManagerInterface;

class Blog {

  /**
  * @var EntityManagerInterface
  */
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function index($categorie)
  {
    /* On récupère les articles via le Repository Article afin de pouvoir appeler le service via le controller,
    ce dernier renverra le tout dans la vue via une boucle for */
    return $this->em->getRepository('BlogBundle:Article')->getArticle($categorie);
  }

  public function delete($id)
  {
    /* On récupère les articles à supprimer via leur id puis on supprime le tout */
    $purge = $this->em->getRepository('BlogBundle:Article')->find($id);
    $this->em->remove($purge);
    $this->em->flush();
  }
}
