<?php

namespace Drupal\forcontu_cache\Controller;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Forcontu Cache routes.
 */
final class ForcontuCacheDefaultController extends ControllerBase {

  /**
   * Connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $database;

  /**
   * CacheBackendInterface.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected CacheBackendInterface $cache;

  /**
   * The controller constructor.
   */
  public function __construct(
    Connection $connection,
    CacheBackendInterface $cacheDefault,
  ) {
    $this->database = $connection;
    $this->cache = $cacheDefault;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('cache.default'),
    );
  }

  /**
   * Builds the response.
   */
  public function build() {
    $start_time = microtime(TRUE);

    $cid = 'forcontu_cache:' . $this->languageManager()->getCurrentLanguage()->getId();
    $data = NULL;
    $from_cache = FALSE;

    if ($cache = $this->cache->get($cid)) {
      $data = $cache->data;
      $from_cache = TRUE;
    }
    else {
      $data = $this->getData();
      $this->cache->set($cid, $data);
    }

    $end_time = microtime(TRUE);
    $duration = $end_time - $start_time;

    if (empty($data)) {
      $build[] = [
        '#markup' => '<h3>' . $this->t('No results found') . '</h3>',
      ];
    }
    else {
      $build[] = [
        '#markup' => '<h3>' . $this->t('Larger articles on this site:') . '</h3>',
      ];
      $build[] = [
        '#theme' => 'item_list',
        '#items' => $data,
        '#cache' => ['max-age' => 0],
      ];

      $build[] = [
        '#markup' => $this->t('Execution time: @time ms', [
          '@time' => number_format($duration * 1000, 2),
        ]),
      ];

      $build[] = [
        '#type' => 'html_tag',
        '#tag' => 'br',
      ];

      $build[] = [
        '#markup' => $this->t('Source: @source', [
          '@source' => !$from_cache ? $this->t('Database query') : $this->t('Cache'),
        ]),
      ];

      $build[] = [
        '#type' => 'html_tag',
        '#tag' => 'br',
      ];

      if ($from_cache) {
        $cache_timestamp = \Drupal::service('date.formatter')->format(intval($cache->created), 'short');
        $build[] = [
          '#markup' => $this->t('Cache time: @cache_time', ['@cache_time' => $cache_timestamp]),
        ];
      }

      return $build;
    }
  }

  /**
   * Get data.
   */
  public function getData() {
    $query = $this->database
      ->select('node__body', 't1')
      ->fields('t1', ['entity_id']);

    $query->join('node_field_data', 't2', 't1.entity_id = t2.nid');
    $query->condition('t2.status', 1);

    $query->addExpression('length(t1.body_value)', 'body_size');
    $query->orderBy('body_size', 'DESC');
    $query->range(0, 10);

    $result = $query->execute();

    $data = NULL;
    foreach ($result as $record) {
      $nid = $record->entity_id;
      $node = $this->entityTypeManager()->getStorage('node')->load($nid);
      $data[$nid] = $node->getTitle();
    }

    return $data;
  }

  /*
   *  // 添加一个 <span> 标签
  $build[] = [
  '#type' => 'html_tag',
  '#tag' => 'span',
  '#attributes' => ['class' => ['custom-span']],
  '#value' => $this->t('This is a custom <span> tag!'),
  ];

  // 添加一个带有子元素的 <p> 标签
  $build[] = [
  '#type' => 'html_tag',
  '#tag' => 'p',
  '#attributes' => ['class' => ['custom-paragraph']],
  '#value' => [
  [
  '#type' => 'html_tag',
  '#tag' => 'strong',
  '#value' => $this->t('This is a custom <strong> tag inside a <p> tag!'),
  ],
  ],
  ];
   */

}
