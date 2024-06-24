<?php

namespace Drupal\forcontu_plugins;

use Drupal\Component\Plugin\PluginBase;

/**
 * T.
 */
abstract class FipsumBase extends PluginBase implements FipsumInterface {

  /**
   * T.
   */
  public function description() {
    return $this->pluginDefinition['description'];
  }

  /**
   * T.
   */
  abstract public function generate($length);

}
