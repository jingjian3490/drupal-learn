<?php

namespace Drupal\forcontu_events\EventSubscriber;

use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description for this subscriber.
 */
final class ForcontuEventsConfigSubscriber implements EventSubscriberInterface {

  /**
   * MessengerInterface.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a ForcontuEventsConfigSubscriber object.
   */
  public function __construct(
    MessengerInterface $messenger,
  ) {
    $this->messenger = $messenger;
  }

  /**
   * T.
   */
  public function onConfigSave(ConfigCrudEvent $event) {
    $this->messenger->addStatus("Event: " . __FUNCTION__);
    $config = $event->getConfig();
    $this->messenger->addStatus("Config: " . $config->getName());
  }

  /**
   * T.
   */
  public function onConfigDelete(ConfigCrudEvent $event) {
    $this->messenger->addStatus("Event: " . __FUNCTION__);
    $config = $event->getConfig();
    $this->messenger->addStatus("Config: " . $config->getName());
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ConfigEvents::SAVE => ['onConfigSave'],
      ConfigEvents::DELETE => ['onConfigDelete'],
    ];
  }

}
