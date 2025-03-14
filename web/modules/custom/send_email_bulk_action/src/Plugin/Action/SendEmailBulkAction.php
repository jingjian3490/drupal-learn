<?php

namespace Drupal\send_email_bulk_action\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\user\Entity\User;

/**
 * Provides the Send Email action.
 *
 * @Action(
 *   id = "send_email_bulk_action",
 *   label = @Translation("Send email to selected users"),
 *   type = "user"
 * )
 */
class SendEmailBulkAction extends ActionBase {
  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function execute($user = NULL) {
    // 获取用户对象.
    if ($user instanceof User) {
      // 向用户发送电子邮件.
      $mail_manager = \Drupal::service('plugin.manager.mail');
      // 模块名称.
      $module = 'send_email_bulk_action';
      // 邮件模板的key.
      $key = 'send_email_action';
      // 获取用户的电子邮件地址.
      $to = $user->getEmail();
      // 邮件参数.
      $params = ['subject' => 'Subject of the email', 'message' => 'Message content'];

      // 发送邮件.
      $mail_manager->mail($module, $key, $to, \Drupal::languageManager()->getDefaultLanguage()->getId(), $params);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function executeMultiple(array $users) {
    // 批量执行.
    foreach ($users as $user) {
      $this->execute($user);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function isEnabled() {
    // 只启用此操作插件.
    return TRUE;
  }

  /**
   * @inheritDoc
   */
  public function access($object, ?AccountInterface $account = NULL, $return_as_object = FALSE) {
    // TODO: Implement access() method.
  }

}
