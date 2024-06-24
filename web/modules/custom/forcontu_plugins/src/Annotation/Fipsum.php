<?php

namespace Drupal\forcontu_plugins\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Fipsum annotation object.
 *
 * @see \Drupal\forcontu_plugins\FipsumPluginManager
 * @see plugin_api
 *
 * @Annotation
 */
class Fipsum extends Plugin {
  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
