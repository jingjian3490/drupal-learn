<?php

namespace Drupal\notification_services\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Mail\MailManager;
use Drupal\file\Entity\File;
use Drupal\notification_services\Notification;

/**
 * Email sender.
 */
class EmailServices {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Main manager.
   *
   * @var \Drupal\Core\Mail\MailManager
   */
  protected $mailManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Notification common service.
   *
   * @var \Drupal\notification_services\Services\NotificationCommonServices
   */
  protected $notificationCommonServices;

  /**
   * Construct.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   Entity type manager.
   * @param \Drupal\Core\Mail\MailManager $mailManager
   *   Mail manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   Config factory.
   * @param \Drupal\notification_services\Services\NotificationCommonServices $notificationCommonService
   *   Notification common service.
   */
  public function __construct(
    EntityTypeManager $entityTypeManager,
    MailManager $mailManager,
    ConfigFactoryInterface $configFactory,
    NotificationCommonServices $notificationCommonService,
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->mailManager = $mailManager;
    $this->configFactory = $configFactory;
    $this->notificationCommonServices = $notificationCommonService;
  }

  /**
   * Send out notification.
   *
   * @param \Drupal\notification_services\Notification $notification
   *   Notification content.
   */
  public function sendNotification(Notification $notification) {
    $switch = \Drupal::state()->get('notification_services.settings')['switch'] ?? 1;
    if (!$switch) {
      \Drupal::logger('Notification')->info(
        t('Notification is disabled. Please contact self services manager to enabled if needed.'));
      return FALSE;
    }

    // 1. Get template
    try {
      $template = $this->notificationCommonServices->getTemplate($notification->templateKey);
    }
    catch (\Exception $e) {
      \Drupal::logger('Notification')->error(
        $notification->templateKey . ':' . $e->getMessage());
      return FALSE;
    }

    // 2. Prepare email subject and body.
    // Placeholder replacement will be done in hook.
    $subject = $template->get('field_email_subject')->value;
    $body = $template->get('field_email_content')->value;
    $params['subject'] = $subject;
    $params['body'] = $body;

    if (!empty($notification->data['@attachment'])) {
      $attachments = $notification->data['@attachment'];
      if (!is_array($attachments)) {
        $attachments = [$notification->data['@attachment']];
      }
      foreach ($attachments as $attachment) {
        $params['attachment'][] = [
          'filepath' => $attachment,
          'filename' => basename($attachment),
          'filemime' => mime_content_type($attachment),
        ];
      }
      unset($notification->data['@attachment']);
    }
    elseif (!empty($template->get('field_attachment')->getValue())) {
      $file_ids = array_column($template->get('field_attachment')->getValue(), 'target_id');
      $attachments = File::loadMultiple($file_ids);
      foreach ($attachments as $attachment) {
        $params['attachment'][] = [
          'filepath' => $attachment->uri->value,
          'filename' => $attachment->filename->value,
          'filemime' => $attachment->filemime->value,
        ];
      }

    }
    if (!empty($notification->data['@cc'])) {
      $cc = $notification->data['@cc'];
      $params['headers']['Cc'] = is_array($cc) ? implode(',', $cc) : $cc;
      unset($notification->data['@cc']);
    }
    if (!empty($notification->data['@bcc'])) {
      $bcc = $notification->data['@bcc'];
      $params['headers']['Bcc'] = is_array($bcc) ? implode(',', $bcc) : $bcc;
      unset($notification->data['@bcc']);
    }
    $params['headers']['MIME-Version'] = '1.0';
    $params['headers']['Content-Type'] = 'text/html; charset=UTF-8; format=flowed';
    $params['headers']['Content-Transfer-Encoding'] = '8Bit';
    $params['token'] = $notification->data;
    if ($notification->combineFooter) {
      $params['token']['@email-footer'] = $this->notificationCommonServices->getEmailFooter();
    }

    // 3. Send mail.
    $result = $this->mailManager->mail(
      'notification_services',
      $notification->templateKey,
      $notification->receiver, NULL,
      $params
    );

    if (!$result['result']) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Send out notification.
   *
   * @param \Drupal\notification_services\Notification $notification
   *   Notification content.
   */
  public function sendNotificationNoTemplate(Notification $notification) {
    $switch = \Drupal::state()->get('notification_services.settings')['switch'] ?? 1;
    if (!$switch) {
      \Drupal::logger('Notification')->info(
        t('Notification is disabled. Please contact self services manager to enabled if needed.'));
      return FALSE;
    }

    // 1. Replace data.
    $params['subject'] = $notification->data['@subject'];
    $params['body'] = $notification->data['@body'];

    // 2. Replace attachment.
    if (!empty($notification->data['@attachment'])) {
      $attachments = $notification->data['@attachment'];
      if (!is_array($attachments)) {
        $attachments = [$notification->data['@attachment']];
      }
      foreach ($attachments as $attachment) {
        $params['attachment'][] = [
          'filepath' => $attachment,
          'filename' => basename($attachment),
          'filemime' => mime_content_type($attachment),
        ];
      }
      unset($notification->data['@attachment']);
    }

    if (!empty($notification->data['@cc'])) {
      $cc = $notification->data['@cc'];
      $params['headers']['Cc'] = is_array($cc) ? implode(',', $cc) : $cc;
      unset($notification->data['@cc']);
    }
    if (!empty($notification->data['@bcc'])) {
      $bcc = $notification->data['@bcc'];
      $params['headers']['Bcc'] = is_array($bcc) ? implode(',', $bcc) : $bcc;
      unset($notification->data['@bcc']);
    }
    $params['headers']['MIME-Version'] = '1.0';
    $params['headers']['Content-Type'] = 'text/html; charset=UTF-8; format=flowed';
    $params['headers']['Content-Transfer-Encoding'] = '8Bit';
    $params['token'] = $notification->data;
    if ($notification->combineFooter) {
      $params['token']['@email-footer'] = $this->notificationCommonServices->getEmailFooter();
    }

    // 3. Send mail.
    $result = $this->mailManager->mail(
      'notification_services',
      $notification->templateKey,
      $notification->receiver, NULL,
      $params
    );

    if (!$result['result']) {
      return FALSE;
    }

    return TRUE;
  }

}
