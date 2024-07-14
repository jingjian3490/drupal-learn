<?php

namespace Drupal\forcontu_views\Plugin\views\sort;

use Drupal\views\Plugin\views\sort\SortPluginBase;

/**
 * Provides highlighted_order ViewsSort.
 *
 * @ViewsSort("highlighted_order")
 */
final class HighlightedOrder extends SortPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query(): void {
    $this->ensureMyTable();
    // 突出显示的节点将始终首先显示.
    $this->query->addOrderBy('forcontu_node_highlighted', 'highlighted', 'DESC');

    // 按日期排序在过滤器配置中设置.
    $this->query->addOrderBy('node_field_data', 'changed', $this->options['order']);
  }

}
