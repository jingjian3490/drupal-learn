<?php

namespace Drupal\forcontu_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a forcontu simple block.
 *
 * @Block(
 *   id = "forcontu_blocks_simple_block",
 *   admin_label = @Translation("Forcontu Simple Block"),
 * )
 */
class SimpleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'label' => 'Custom Title',
      'label_display' => FALSE,
    ];
  }

}
