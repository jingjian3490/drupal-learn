<?php

namespace Drupal\forcontu_plugins\Plugin\Fipsum;

use Drupal\forcontu_plugins\FipsumBase;

/**
 * Provides a LoremIpsum text.
 *
 * @Fipsum(
 *   id = "lorem_ipsum",
 *   description = @Translation("Lorem Ipsum text")
 * )
 */
class LoremIpsum extends FipsumBase {

  /**
   * T.
   */
  public function generate($length = 100) {
    return substr(file_get_contents('http://loripsum.net/api/1/verylong/plaintext'), 0, $length) . '.';
  }

}
