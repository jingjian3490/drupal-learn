<?php

declare(strict_types=1);

namespace Drupal\forcontu_entities_message;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of message type entities.
 *
 * @see \Drupal\forcontu_entities_message\Entity\MessageType
 */
final class MessageTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['label'] = $this->t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    $row['label'] = $entity->label();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    $build = parent::render();

    $build['table']['#empty'] = $this->t(
      'No message types available. <a href=":link">Add message type</a>.',
      [':link' => Url::fromRoute('entity.forcontu_entities_message_type.add_form')->toString()],
    );

    return $build;
  }

}
