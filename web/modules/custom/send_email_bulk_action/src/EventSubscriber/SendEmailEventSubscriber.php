<?php

namespace Drupal\send_email_bulk_action\EventSubscriber;

use Drupal\send_email_bulk_action\Event\SendEmailEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscribes to the Send Email event.
 */
class SendEmailEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      SendEmailEvent::EVENT_NAME => 'onSendEmail',
    ];
  }

  /**
   * Handles the send email event.
   *
   * @param \Drupal\send_email_bulk_action\Event\SendEmailEvent $event
   *   The send email event.
   */
  public function onSendEmail(SendEmailEvent $event) {
    // Add your email sending logic here.
    $user = $event->getUser();
    \Drupal::messenger()->addMessage('Triggered email for ' . $user->getEmail());
  }

}
