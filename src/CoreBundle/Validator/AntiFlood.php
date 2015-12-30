<?php

namespace CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class AntiFlood extends Constraint {

  public $message = "Vous avez déjà posté un message depuis moins de 15 secondes";

  public function validatedBy()
  {
    return corebundle_antiflood; // On appel le service antiflood afin de simplifier le tout
  }

}
