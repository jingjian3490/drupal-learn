<?php

namespace Drupal\forcontu_plugins;

/**
 * Interface for all Fipsum type plugins.
 */
interface FipsumInterface {

  /**
   * T.
   */
  public function description();

  /**
   * T.
   */
  public function generate($length);

}
