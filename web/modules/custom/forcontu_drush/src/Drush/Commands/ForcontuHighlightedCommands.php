<?php

namespace Drupal\forcontu_drush\Drush\Commands;

use Consolidation\AnnotatedCommand\Attributes\FieldLabels;
use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drush\Attributes\Argument;
use Drush\Attributes\Command;
use Drush\Commands\AutowireTrait;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Custom drush command class.
 */
class ForcontuHighlightedCommands extends DrushCommands {

  use AutowireTrait;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
    protected Connection $database,
  ) {
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('database'),
    );
  }

  /**
   * Return a list of highlighted nodes.
   */
  #[Command(
    name: 'forcontu:highlighted',
    aliases: ['fhl'],
  )]
  #[Argument(
    name: 'test1',
    description: 'Test highlighted command',
  )]
  #[FieldLabels(
    labels: [
      'nid' => 'Node id',
      'title' => 'Node title',
    ],
  )]
  public function list($test1, $options = ['format' => 'table']): RowsOfFields {

    $query = $this->database->select('forcontu_node_highlighted', 'f')
      ->fields('f', ['nid'])
      ->condition('f.highlighted', 1);

    $result = $query->execute();

    $output = [];
    foreach ($result as $record) {
      $nid = $record->nid;
      $node = $this->entityTypeManager->getStorage('node')->load($nid);
      $title = $node->getTitle();
      $output[$nid] = [
        'nid' => $nid,
        'title' => $node->getTitle(),
      ];
    }

    if (empty($output)) {
      $this->logger()->success(dt('No highlighted nodes found.'));
    }

    return new RowsOfFields($output);
  }

}
