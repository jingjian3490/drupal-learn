<?php

namespace Drupal\forcontu_blocks\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a node vote block.
 *
 * @Block(
 *   id = "forcontu_blocks_node_voting_block",
 *   admin_label = @Translation("Node Voting"),
 * )
 */
class NodeVoteBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * RouteMatchInterface.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected RouteMatchInterface $currentRouteMatch;

  /**
   * FormBuilderInterface.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected FormBuilderInterface $formBuilder;

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    FormBuilderInterface $form_builder,
    RouteMatchInterface $current_route_match,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('current_route_match'),
    );
  }

  /**
   * T.
   */
  protected function blockAccess(AccountInterface $account) {
    $node = $this->currentRouteMatch->getParameter('node');
    if ($node && $account->isAuthenticated()) {
      return AccessResult::allowed();
    }
    else {
      return AccessResult::forbidden();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return $this->formBuilder->getForm('Drupal\forcontu_blocks\Form\NodeVoteForm');
  }

}
