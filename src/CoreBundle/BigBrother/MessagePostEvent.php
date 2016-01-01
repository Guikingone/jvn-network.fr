<?php

namespace CoreBundle\BigBrother;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class MessagePostEvent extends Event {

  protected $message;

  protected $user;

  public function __construct($message, $user)
  {
    $this->message = $message;
    $this->user    = $user;
  }

  public function getMessage()
  {
    return $this->message;
  }

  public function setMessage($message)
  {
    return $this->message = $message;
  }

  public function setUser($user)
  {
    return $this->user = $user;
  }

  public function getUser()
  {
    return $this->user;
  }
}
