<?php

namespace Drupal\forcontu_views\Plugin\views\argument;

use Drupal\taxonomy\Plugin\views\argument\Taxonomy;

/**
 * Defines a filter for Taxonomy Term Slugs.
 *
 * // Don't success.
 *
 * @ViewsArgument("term_slug")
 */
class TermSlug extends Taxonomy {

  /**
   * {@inheritdoc}
   */
  public function setArgument($arg) {
    if ($this->isException($arg)) {
      return parent::setArgument($arg);
    }
    // 参数将是术语名称.
    $tid = $this->convertSlugToTid($arg);
    $tid = is_numeric($arg) ? $arg : $this->convertSlugToTid($arg);
    $this->argument = (int) $tid;
    return $this->validateArgument($tid);
  }

  /**
   * Get taxonomy term ID from a slug.
   *
   * @return int
   *   Taxonomy term ID.
   */
  protected function convertSlugToTid($slug) {
    $query = $this->termStorage->getQuery()
      ->accessCheck('FALSE')
      ->condition('field_slug', $slug);

    // 允许的词汇表.
    if (isset($this->options['specify_validation']) && isset($this->options['validate_options']['bundles'])) {
      $query->condition('vid', $this->options['validate_options']['bundles']);

    }
    // 结果术语ID.
    $tids = $query->execute();
    return $tids ? reset($tids) : FALSE;
  }

}
