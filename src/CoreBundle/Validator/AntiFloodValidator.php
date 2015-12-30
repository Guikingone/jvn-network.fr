<?php

namespace CoreBundle\Validator;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityMangerInterface;
use Symfony\Component\HtppFoundation\RequestStack;

class AntiFloodValidator extends ConstraintValidator {

  private $requeststack;

  private $em;

  private $isflood;

  public function __construct(RequestStack $requeststack, EntityMangerInterface $em)
  {
    $this->requeststack = $requeststack;
    $this->em           = $em;
  }

  public function validate($value, Constraints $constraint)
  {
    $request = $this->requeStack->getCurrentRequest();
    $ip = $request->getClientIp();
    $isflood = $this->em
                    ->getRepository('BlogBundle:Article')
                    ->isFlood($ip, 15);

    if($isflood){
      $this->context->addViolation($constraint->message);
    }
  }

  public function isFlood(){
    if(strlen($value) < 3){
      return $this->isflood;
    }
  }
}
