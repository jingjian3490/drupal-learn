<?php

namespace Drupal\notification_services\Services;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\node\NodeInterface;
use PHPUnit\Runner\Exception;

/**
 * Notification common service.
 */
class NotificationCommonServices {

  const EMAIL_TEMPLATE_FOOTER_DESCRIPTION = 'Notification footer';

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Construct.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   Entity type manager.
   */
  public function __construct(
    EntityTypeManager $entityTypeManager,
  ) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Get email template.
   *
   * @param string $templateKey
   *   Template key.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   Template node.
   */
  public function getTemplate($templateKey) {
    $templates = $this->entityTypeManager->getStorage('node')
      ->loadByProperties([
        'type' => 'notification_templates',
        'field_template_key' => $templateKey,
        'status' => NodeInterface::PUBLISHED,
      ]);

    if (empty($templates)) {
      throw new Exception("Template not found");
    }

    return array_pop($templates);
  }

  /**
   * Get footer html.
   *
   * @return string
   *   Block body.
   */
  public function getEmailFooter() {
    // Block don't have a machine name. So we have to use id here.
    $blocks = $this->entityTypeManager->getStorage('block_content')
      ->loadByProperties([
        'info' => self::EMAIL_TEMPLATE_FOOTER_DESCRIPTION,
      ]);

    if (empty($blocks)) {
      return NULL;
    }

    $block = array_pop($blocks);

    return new FormattableMarkup($block->body->value, []);
  }

}
