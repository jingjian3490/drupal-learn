<?php

namespace Drupal\forcontu_events\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\user\UserInterface;

/**
 * Event that is fired when a user logs in.
 */
class UserLoginEvent extends Event {

  const USER_LOGIN = 'forcontu_events_user_login';

  /**
   * UserInterface.
   *
   * @var \Drupal\user\UserInterface
   */
  public UserInterface $account;

  public function __construct(UserInterface $account) {
    $this->account = $account;
  }

}
