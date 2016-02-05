<?php

namespace CoreBundle\Blog;

use Doctrine\ORM\EntityManagerInterface;

class Blog {

  /**
  * @var EntityManagerInterface
  */
  protected $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function index($categorie)
  {
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
