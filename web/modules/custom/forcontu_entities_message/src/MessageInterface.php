<?php

namespace Drupal\forcontu_entities_message;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\UserInterface;

/**
 * Provides an interface defining a message entity type.
 */
interface MessageInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the Message type.
   *
   * @return string
   *   The Message type.
   */
  public function getType();

  /**
   * Gets the Message subject.
   *
   * @return string
   *   Subject of the Message.
   */
  public function getSubject();

  /**
   * Sets the Message subject.
   *
   * @param string $subject
   *   The Message subject.
   *
   * @return \Drupal\forcontu_entities_message\Entity\Message
   *   The called Message entity.
   */
  public function setSubject($subject);

  /**
   * Gets the Message creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Message.
   */
  public function getCreatedTime();

  /**
   * Sets the Message creation timestamp.
   *
   * @param int $timestamp
   *   The Message creation timestamp.
   *
   * @return \Drupal\forcontu_entities_message\Entity\MessageInterface
   *   The called Message entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Message published status indicator.
   *
   * Unpublished Message are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Message is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Message.
   *
   * @param bool $published
   *   TRUE to set this Message to published, FALSE to set it to unpublished.
   *
   * @return $this
   *   The called Message entity.
   */
  public function setPublished($published);

  /**
   * Gets the To user id.
   *
   * @return int
   *   The user id.
   */
  public function getUserToId();

  /**
   * Sets the To user id.
   *
   * @param int $uid
   *   To user id.
   *
   * @return $this
   */
  public function setUserToId($uid);

  /**
   * Gets the To user object.
   *
   * @return \Drupal\user\UserInterface
   *   The user object.
   */
  public function getUserTo();

  /**
   * Sets the To user object.
   *
   * @param string $account
   *   The user object.
   *
   * @return $this
   */
  public function setUserTo(UserInterface $account);

  /**
   * Gets the Content.
   *
   * @return string
   *   Message content.
   */
  public function getContent();

  /**
   * Sets the message's content.
   *
   * @param string $content
   *   Message's content.
   *
   * @return $this
   */
  public function setContent($content);

  /**
   * Returns the Message read indicator.
   *
   * @return bool
   *   T.
   */
  public function isRead();

  /**
   * Sets the read status of a Message.
   *
   * @param bool $read
   *   TRUE to set this Message to read.
   *
   * @return $this
   */
  public function setRead($read);

}
