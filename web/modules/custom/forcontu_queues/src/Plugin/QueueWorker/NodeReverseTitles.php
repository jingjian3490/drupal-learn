<?php

namespace Drupal\forcontu_queues\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Defines 'node_reverse_titles' queue worker.
 *
 * @QueueWorker(
 *   id = "node_reverse_titles",
 *   title = @Translation("Node Reverse Titles"),
 *   cron = {"time" = 5}
 * )
 */
class NodeReverseTitles extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $id = $data['id'];
    $title = $data['title'];

    // 反转标题字符串.
    $new_title = strrev($title);

    $storage = \Drupal::entityTypeManager()->getStorage('node');

    $node = $storage->load($id);
    $node->setTitle($new_title);
    $node->save();

    \Drupal::logger('forcontu_queues')->notice('Node @id has been processed.', ['@id' => $id]);
    // 激活以在执行中添加延迟
    // sleep(1);.
  }

}
