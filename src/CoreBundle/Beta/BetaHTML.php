<?php

namespace CoreBundle\Beta;

use Symfony\Component\HttpFoundation\Response;

class BetaHTML {

  // On ajoute "Beta" à une réponse
  public function displayBeta(Response $response, $remainingDays)
  {
    $content = $response->getContent();

    $html = '<span style="color: red: font-size: 0.5em;"> - Beta : J - '.(int)
    $remainingDays.' !</span>';

    // On insére le code dans le premier <h1>
    $content = preg_replace(
      '#<h1>(.*?)</h1>#iU',
      '<h1>$1'.$html.'</h1>',
      $content,
      1);

      $content->setContent($content);
      return $response;
  }

}
