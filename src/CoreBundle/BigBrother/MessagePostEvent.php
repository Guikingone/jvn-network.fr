<?php

namespace CoreBundle\BigBrother;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class MessagePostEvent extends Event {

  protected $message;

  protected $auteur;

  public function __construct($message, $auteur)
  {
    $this->message = $message;
    $this->auteur    = $auteur;
  }

  public function getMessage()
  {
    return $this->message;
  }

  public function setMessage($message)
  {
    return $this->message = $message;
  }

  public function setUser($auteur)
  {
    return $this->auteur = $auteur;
  }

  public function getUser()
  {
    return $this->auteur;
  }
}
