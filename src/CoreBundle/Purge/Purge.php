<?php

namespace CoreBundle\Purge;

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

  public function purgeSujet($id)
  {
    /* On récupère les sujets à supprimer via leur id et l'action removeSujet puis on flush
    afin de valider la suppression */
    $purge = $this->em
                  ->getRepository('ForumsBundle:Sujet')
                  ->find($id);
    $this->em->remove($purge);
    $this->em->flush();
  }

  public function purgeMessage($id)
  {
    /* On récupère les messages à supprimer via leur id et l'action removeMessage puis on flush
    afin de valider la suppression */
    $purge = $this->em
                  ->getRepository('ForumsBundle:Message')
                  ->find($id);
    $this->em->remove($purge);
    $this->em->flush();
  }

  public function purgeCommentaires($id)
  {
    /* On récupère les commentaires à supprimer via leur id et l'action removeCommentaire puis on flush
    afin de valider la suppression */
    $purge = $this->em->getRepository('BlogBundle:Commentaires')
                      ->find($id);
    $this->em->remove($purge);
    $this->em->flush();
  }

  public function purgeUser($id)
  {
    /* On récupère les utilisateurs à supprimer via leur id et l'action removeUtilisateur puis on flush
    afin de valider la suppression */
    /* Attention, cette action n'est à effectuer qu'après validation auprès de l'ensemble de l'équipe,
    la suppression est irréversible et ne saurait être validée sans raison valable */
    $purge = $this->em
                  ->getRepository('UserBundle:User')
                  ->find($id);
    $this->em->remove($purge);
    $this->em->flush();
  }
}
