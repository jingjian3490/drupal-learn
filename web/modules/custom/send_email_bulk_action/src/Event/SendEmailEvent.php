<?php

namespace Drupal\send_email_bulk_action\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Defines the Send Email Event.
 */
class SendEmailEvent extends Event {
  const EVENT_NAME = 'send_email_rule';

  /**
   * The user entity.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $user;

  /**
   * Constructs the event.
   *
   * @param \Drupal\user\Entity\User $user
   *   The user entity.
   */
  public function __construct($user) {
    $this->user = $user;
  }

  /**
   * Gets the user entity.
   *
   * @return \Drupal\user\Entity\User
   *   The user entity.
   */
  public function getUser() {
    return $this->user;
  }
}
