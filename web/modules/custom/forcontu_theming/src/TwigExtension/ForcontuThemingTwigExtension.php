<?php

namespace Drupal\forcontu_theming\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension.
 */
class ForcontuThemingTwigExtension extends AbstractExtension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new TwigFunction('loripsum', [$this, 'loripsum']),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return [
      new TwigFilter('space_replace', [$this, 'spaceReplace']),
    ];
  }

  /**
   * T.
   */
  public function loripsum($length = 50) {
    return substr(file_get_contents('http://loripsum.net/api/long/plaintext'), 0, $length) . '.';
  }

  /**
   * T.
   */
  public function spaceReplace($string) {
    return str_replace(' ', '-', $string);
  }

}
