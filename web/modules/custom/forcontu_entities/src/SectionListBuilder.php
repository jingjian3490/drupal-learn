<?php

namespace Drupal\forcontu_entities;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of sections.
 */
final class SectionListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['label'] = $this->t('Section');
    $header['id'] = $this->t('Machine name');
    $header['urlPattern'] = $this->t('URL pattern');
    $header['color'] = $this->t('Color');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\forcontu_entities\SectionInterface $entity */
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    $row['urlPattern'] = $entity->getUrlPattern();
    $row['color'] = $entity->getColor();
    return $row + parent::buildRow($entity);
  }

}
