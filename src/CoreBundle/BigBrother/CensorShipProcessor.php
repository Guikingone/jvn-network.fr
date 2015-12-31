<?php

namespace CoreBundle\BigBrother;

use Symfony\Component\Core\Security\User\UserInterface;

class CensorShipProcessor {

  protected $mailer;

  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  public function notifyEmail($message, UserInterface $user)
  {
    /* On envoit un message à l'administrateur lorsque qu'un utilisateur surveillé poste un message */
    $message = \Swift_Message::newInstance()
                ->setSubject("Nouveau message d'un utilisateur surveillé")
                ->setFrom('admon@votresite.com')
                ->setTo('admin@votresite.com')
                ->setBody('l\'utilisateur surveillé' . $user->getUsername() . ' à poster le message suivant ' . $message. ".");

    $this->mailer->send($message);
  }

  public function censorMessage($message)
  {
    /* Au besoin, on censure les messages contenant des mots interdits */
    $message = str_replace(array('top secret', 'mot interdit'), '', $message);
    return $message;
  }
}
