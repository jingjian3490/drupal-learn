<?php

namespace Drupal\forcontu_views\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\FilterPluginBase;

/**
 * Provides highlighted_recent ViewsFilter.
 *
 * @ViewsFilter("highlighted_recent")
 */
final class HighlightedRecent extends FilterPluginBase {

  /**
   * {@inheritdoc}
   */
  public function adminSummary() {}

  /**
   * {@inheritdoc}
   */
  protected function operatorForm(&$form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function canExpose() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $table = $this->ensureMyTable();
    $currentTime = \Drupal::time()->getCurrentTime();
    // 减去当前日期的3天.
    $minTime = $currentTime - (3 * 24 * 3600);
    $snippet = "$table.highlighted = 1 AND node_field_data.changed > $minTime";
    $this->query->addWhereExpression($this->options['group'], $snippet);
  }

}
