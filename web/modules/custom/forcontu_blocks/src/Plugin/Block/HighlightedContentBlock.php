<?php

namespace Drupal\forcontu_blocks\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\mysql\Driver\Database\mysql\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a highlighted content block.
 *
 * @Block(
 *   id = "forcontu_blocks_highlighted_content_block",
 *   admin_label = @Translation("Highlighted Content"),
 * )
 */
class HighlightedContentBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Connection.
   *
   * @var \Drupal\mysql\Driver\Database\mysql\Connection
   */
  protected $database;

  /**
   * AccountInterface.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $current_user, Connection $database) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
    $this->database = $database;
  }

  /**
   * T.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return [
      'block_message' => $this->t('Hello world!'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {

    $form['forcontu_blocks_block_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Display message'),
      '#default_value' => $this->configuration['block_message'],
    ];

    $range = range(1, 10);
    $form['forcontu_blocks_node_number'] = [
      '#type' => 'select',
      '#title' => $this->t('Number of nodes'),
      '#default_value' => $this->configuration['node_number'],
      '#options' => array_combine($range, $range),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('forcontu_blocks_node_number')) < 10) {
      $form_state->setErrorByName('forcontu_blocks_node_number',
        $this->t('The text must be at least 10 characters long'));
    }
    if ($form_state->getValue('forcontu_blocks_block_message') != 10) {
      $form_state->setErrorByName('forcontu_blocks_block_message',
        $this->t('The text must be at least 10 characters long'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    $this->configuration['example'] = $form_state->getValue('example');

    $this->configuration['block_message'] = $form_state->getValue('forcontu_blocks_block_message');
    $this->configuration['node_number'] = $form_state->getValue('forcontu_blocks_node_number');
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $node_number = $this->configuration['node_number'];
    $block_message = $this->configuration['block_message'];

    $build[] = [
      '#markup' => "<h3>" . $this->t($block_message) . "</h3>",
    ];

    $result = $this->database->select('node', 'n')
      ->fields('n', ['nid'])
      ->condition('type', 'article')
      ->orderBy('nid', 'DESC')
      ->range(0, $node_number)
      ->execute();

    $list = [];
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');

    foreach ($result as $record) {
      $node = $node_storage->load($record->nid);
      $list[] = $node->toLink($node->getTitle())->toRenderable();
    }

    if (empty($list)) {
      $build[] = [
        '#markup' => '<h3>' . $this->t('No results found') . '</h3>',
      ];
    }
    else {
      // 2d
      $build[] = [
        '#theme' => 'item_list',
        '#items' => $list,
        '#cache' => ['max-age' => 0],
      ];
    }

    return $build;
  }

}
