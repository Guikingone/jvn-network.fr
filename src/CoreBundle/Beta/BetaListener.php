<?php

namespace CoreBundle\Beta;

use Symfony\Component\HttpFoundation\Reponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class BetaListener {

  protected $betaHTML;

  /* On définit la date de la version bêta :
  -- Avant cette date, on affichera "Beta J -"
  -- Après cette date, on affichera rien
  */
  protected $endDate;

  public function __construct(BetaHtml $betaHtml, $endDate)
  {
    $this->betaHtml = $betaHtml;
    $this->endDate = new \Datetime($endDate);
  }

  public function processBeta(FilterResponseEvent $event)
  {
    /* On teste si la requête est la requête principale, on récupère la réponse que le gestionnaire
    à insérée dans l'évènements, on modifie la réponse et on insère la réponse dans l'évènement */
    if(!$event->isMasterRequest()){
      return;
    }

    // Si la date est dépassée, on ne fait rien
    if($remainingDays <= 0){
      return;
    }

    $remainingDays = $this->endDate->diff(new \Datetime())->format('%d%');
    $response = $this->betaHTML->displayBeta($event->getResponse(), $remainingDays);
    $event->setResponse($response);
  }
}
