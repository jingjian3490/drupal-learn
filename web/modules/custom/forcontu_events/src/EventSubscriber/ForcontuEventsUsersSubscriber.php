<?php

namespace Drupal\forcontu_events\EventSubscriber;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\forcontu_events\Event\UserLoginEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Forcontu Events event subscriber.
 */
class ForcontuEventsUsersSubscriber implements EventSubscriberInterface {
  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs event subscriber.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * T.
   */
  public function onUserLogin(UserLoginEvent $event) {
    $this->messenger->addStatus(__FUNCTION__);
    $this->messenger->addStatus(t("Welcome back, %username", ['%username' => $event->account->getAccountName()]));
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      UserLoginEvent::USER_LOGIN => ['onUserLogin'],
    ];
  }

}
