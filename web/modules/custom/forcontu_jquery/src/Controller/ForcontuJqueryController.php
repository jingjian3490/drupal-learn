<?php

namespace Drupal\forcontu_jquery\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Forcontu jquery routes.
 */
final class ForcontuJqueryController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function fadeout(): array {

    $build['text'] = [
      '#markup' => '<p>' . $this->t('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tristique enim lorem, quis imperdiet ante luctus non. Phasellus sapien neque, placerat sed odio ut, efficitur tincidunt dui.') . '</p>',
    ];
    $build['temp_text'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#attributes' => [
        'class' => 'fadeout',
      ],
      '#value' => $this->t('This text will disappear in 5 seconds...'),
      '#attached' => [
        'library' => [
          'forcontu_jquery/forcontu_jquery.fadeout',
        ],
      ],
    ];
    $build['#attached']['drupalSettings']['forcontu_jquery']['testValue']['foo'] = 'bar';

    return $build;
  }

  /**
   * Base.
   */
  public function base(): array {

    $build = [
      '#theme' => 'jquery_base_theme',
      '#title' => $this->t('Hello World!'),
      '#description' => $this->t('This is a custom page rendered with a Twig template.'),
      '#attached' => [
        'library' => [
          'forcontu_jquery/forcontu_jquery.fadeout',
        ],
      ],
    ];
    $build['#attached']['drupalSettings']['forcontu_jquery']['testValue']['foo'] = 'bar';

    return $build;
  }

}
