<?php

namespace Drupal\notification_services;

/**
 * Notification.
 */
class Notification {

  // Notification type value.
  const NOTIFICATION_TYPE_EMAIL = 1;

  /**
   * Notification type.
   *
   * @var int
   */
  public $type;

  /**
   * Template key.
   *
   * @var string
   */
  public $templateKey;

  /**
   * The target address.
   *
   * @var string
   */
  public $receiver;

  /**
   * The data to be fill into the template.
   *
   * @var array
   */
  public $data;

  /**
   * Whether to combine the footer.
   *
   * @var bool
   */
  public $combineFooter;

  /**
   * Notification constructor..
   *
   * @param int $type
   *   Notification type , should be email or sms.
   * @param string $templateKey
   *   Template key.
   * @param string $receiver
   *   The target address.
   * @param array $data
   *   Data to fill into the template.
   * @param bool $combineFooter
   *   Whether to combine the footer.
   */
  public function __construct(
    int $type,
    string $templateKey,
    string $receiver,
    array $data,
    bool $combineFooter = TRUE,
  ) {
    $this->type = $type;
    $this->templateKey = $templateKey;
    $this->receiver = $receiver;
    $this->data = $data;
    $this->combineFooter = $combineFooter;
  }

}
