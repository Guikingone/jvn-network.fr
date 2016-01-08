<?php

namespace ForumsBundle\Purge;

use Doctrine\ORM\EntityManagerInterface;

class Purge {

  /**
  * @var EntityManagerInterface
  */
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function purge($id)
  {
    /* On récupère les sujets à supprimer via leur id et l'action removeSujet puis on flush
    afin de valider la suppression */
    $purge = $this->em
                  ->getRepository('ForumsBundle:Sujet')
                  ->removeSujet($id);

    $this->em->flush();
  }
}
