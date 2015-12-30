<?php

namespace CoreBundle\Validator;

use Symfony\Component\Validator\Constraints;

/**
* @Annotation
*/
class AntiFlood extends Constraints {

  public $message = "Vous avez déjà posté un message depuis moins de 15 secondes";

  public function validatedBy()
  {
    return corebundle_antiflood; // On appel le service antiflood afin de simplifier le tout
  }

}
