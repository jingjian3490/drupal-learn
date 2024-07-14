<?php

namespace Drupal\forcontu_views\Plugin\views\field;

use Drupal\Component\Render\MarkupInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Provides PublishedByOn field handler.
 *
 * @ViewsField("published_by_on")
 */
final class PublishedByOn extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query(): void {
    // For non-existent columns (i.e. computed fields) this method must be
    // empty.
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values): array {

    $node = $values->_entity;
    $author = $node->getOwner()->getDisplayName();
    $created_time = \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'short');

    $build = [
      '#markup' => $this->t("Published by @user on @date", [
        '@user' => $author,
        '@date' => $created_time,
      ]),
    ];
    return $build;
  }

}
