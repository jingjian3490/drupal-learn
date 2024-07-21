<?php

namespace Drupal\forcontu_events\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\forcontu_events\Event\UserLoginEvent;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Returns responses for Forcontu Events routes.
 */
final class ForcontuEventsController extends ControllerBase {

  /**
   * Event dispatcher.
   *
   * @var \Symfony\Contracts\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * The controller constructor.
   */
  public function __construct(
    EventDispatcherInterface $eventDispatcher,
  ) {
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('event_dispatcher'),
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {
    $account = $this->currentUser();
    $uid = $account->id();
    $user = User::load($uid);

    $event = new UserLoginEvent($user);
    $this->eventDispatcher->dispatch($event, UserLoginEvent::USER_LOGIN);

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
