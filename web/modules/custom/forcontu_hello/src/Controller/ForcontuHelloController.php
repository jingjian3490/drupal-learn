<?php

namespace Drupal\forcontu_hello\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * TD.
 */
class ForcontuHelloController extends ControllerBase {

  /**
   * TD.
   */
  public function hello(): array {
    return [
      '#markup' => '<p>' . $this->t('Hello, world! <a href = :a > 22 </a>', [':a' => 'https://drupal-learn.ddev.site/']) . '</p>',
    ];
  }

}
