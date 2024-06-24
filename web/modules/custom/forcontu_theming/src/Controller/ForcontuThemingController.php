<?php

namespace Drupal\forcontu_theming\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Returns responses for Forcontu theming routes.
 */
class ForcontuThemingController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function render(): array {

    // 示例 1: markup.
    $build['forcontu_theming_markup'] = [
      '#markup' => '<p>' . $this->t('Lorem ipsum dolor sit amet, consectetur adipiscing elit.') . '</p>',
    ];

    // 示例 2: table.
    $header = ['Column 1', 'Column 2', 'Column 3'];
    $rows[] = ['A', 'B', 'C'];
    $rows[] = ['D', 'E', 'F'];

    $build['forcontu_theming_table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    // 示例 3: list.
    $list = ['Item 1', 'Item 2', 'Item 3'];

    $build['forcontu_theming_list'] = [
      '#theme' => 'item_list',
      '#title' => $this->t('List of items'),
      '#list_type' => 'ol',
      '#items' => $list,
    ];

    $build['dropbutton'] = [
      '#type' => 'dropbutton',
      '#links' => [
        'view' => [
          'title' => $this->t('View'),
          'url' => Url::fromRoute('forcontu_pages.tab2'),
        ],
        'edit' => [
          'title' => $this->t('Edit'),
          'url' => Url::fromRoute('forcontu_pages.tab2'),
        ],
        'delete' => [
          'title' => $this->t('Delete'),
          'url' => Url::fromRoute('forcontu_pages.tab2'),
        ],
      ],
    ];

    $build['more_link'] = [
      '#type' => 'more_link',
      '#url' => Url::fromRoute('forcontu_pages.tab2'),
    ];

    $build['item_dimensions'] = [
      '#theme' => 'forcontu_theming_dimensions',
      '#attached' => [
        'library' => [
          'forcontu_theming/forcontu_theming.css',
        ],
      ],
      '#length' => 12,
      '#width' => 8,
      '#height' => 24,
    ];

    $build['type_item_dimensions'] = [
      '#type' => 'custom_render_element',
      '#length' => 12,
      '#width' => 8,
      '#height' => 24,
    ];

    return $build;
  }

}
