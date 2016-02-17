<?php

namespace CoreBundle\Outils;

class Slug {

  public function sluggify($string)
  {
    return preg_replace('/[^a-z0-9]/', '-', strtolower(trim(strip_tags($string))));
    )
  }
}
