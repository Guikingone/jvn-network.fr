<?php

namespace CoreBundle\BigBrother;

class CensorShipListenet {

  protected $processor;

  protected $listUsers = array();

  public function __construct(CensorShipProcessor $processor, $listUsers)
  {
    $this->processor = $processor;
    $this->listUsers = $listUsers;
  }

  public function processMessage(MessagePostEvents $event)
  {
    /* On active la surveillance si l'utilisateur est dans la liste, on envoie un mail à l'administrateur,
    on censure le message et on enregistre ce message dans l'event */
    if(in_array($event->getUser()->getId(), $this->listUsers)){
      $this->processor->notifyEmail($event->getMessage(), $even->getUser());
      $message = $this->processor->censorMessage($event->getMessage());
      $event->setmessage($message);
    }
  }
}
