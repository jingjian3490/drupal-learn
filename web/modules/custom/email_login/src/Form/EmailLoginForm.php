<?php

namespace Drupal\email_login\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\honeypot\HoneypotService;
use Drupal\notification_services\Notification;
use Drupal\notification_services\Services\EmailServices;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 *
 */
class EmailLoginForm extends FormBase {

  /**
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Notification.
   *
   * @var \Drupal\notification_services\Services\EmailServices
   */
  protected $notificationEmailServices;

  /**
   * Honeypot service.
   *
   * @var \Drupal\honeypot\HoneypotService
   */
  protected HoneypotService $honeypotService;

  /**
   * 构造函数。.
   */
  public function __construct(
    MailManagerInterface $mail_manager,
    MessengerInterface $messenger,
    EmailServices $notification_email_services,
    HoneypotService $honeypotService,
  ) {
    $this->mailManager = $mail_manager;
    $this->messenger = $messenger;
    $this->notificationEmailServices = $notification_email_services;
    $this->honeypotService = $honeypotService;
  }

  /**
   * 创建表单时的依赖注入。.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.mail'),
      $container->get('messenger'),
      $container->get('notification_services.email_service'),
      $container->get('honeypot'),
    );
  }

  /**
   * 返回表单ID.
   */
  public function getFormId() {
    return 'email_login_form';
  }

  /**
   * 构建表单.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // 检查是否处于验证阶段。.
    $step = $form_state->get('step') ?? 'request';

    if ($step === 'request') {
      // 请求验证码阶段.
      $form['email'] = [
        '#type' => 'email',
        '#title' => $this->t('电子邮件'),
        '#required' => TRUE,
      ];

      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('请求验证码'),
      ];
    }
    elseif ($step === 'verify') {
      // 验证验证码阶段.
      $form['email'] = [
        '#type' => 'email',
        '#title' => $this->t('电子邮件'),
        '#required' => TRUE,
        '#default_value' => $form_state->get('email') ?? '',
        '#disabled' => TRUE,
      ];

      $form['code'] = [
        '#type' => 'textfield',
        '#title' => $this->t('验证码'),
        '#required' => TRUE,
        '#size' => 6,
        '#maxlength' => 6,
        '#attributes' => ['pattern' => '\d{6}', 'title' => $this->t('请输入6位数字验证码')],
      ];

      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('登录'),
      ];

      // 提供重新发送验证码的链接.
      $resend_url = Url::fromRoute('email_login.form')->toString();
      $form['resend'] = [
        '#type' => 'markup',
        '#markup' => $this->t('没有收到验证码？ <a href="@url">重新发送</a>.', ['@url' => $resend_url]),
        '#allowed_tags' => ['a'],
      ];
    }

    $this->honeypotService->addFormProtection(
      $form,
      $form_state,
      ['honeypot', 'time_restriction']
    );

    $form['submission_check'] = [
      '#type' => 'hidden',
      '#default_value' => time(),
    ];

    return $form;
  }

  /**
   * 表单验证.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step') ?? 'request';

    if ($step === 'request') {
      $email = $form_state->getValue('email');
      $users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties(['mail' => $email]);

      if (empty($users)) {
        $form_state->setErrorByName('email', $this->t('该电子邮件未注册。'));
      }
    }
    elseif ($step === 'verify') {
      $email = $form_state->getValue('email');
      $code = $form_state->getValue('code');

      $users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties(['mail' => $email]);
      if (empty($users)) {
        $form_state->setErrorByName('email', $this->t('该电子邮件未注册。'));
        return;
      }

      $user = reset($users);

      // 查询验证码记录.
      $result = \Drupal::database()->select('email_login_codes', 'elc')
        ->fields('elc', ['code', 'expires'])
        ->condition('uid', $user->id())
        ->execute()
        ->fetchAssoc();

      if (!$result) {
        $form_state->setErrorByName('code', $this->t('未找到验证码记录。请重新请求验证码。'));
        return;
      }

      if ($result['code'] != $code) {
        $form_state->setErrorByName('code', $this->t('验证码不正确。'));
        return;
      }

      $current_time = new \DateTime();
      $expires = new \DateTime($result['expires']);

      if ($current_time > $expires) {
        $form_state->setErrorByName('code', $this->t('验证码已过期。请重新请求验证码。'));
        return;
      }

//      if (time() - $form_state->getValue('submission_check') < 10) {
//        $form_state->setErrorByName('email', $this->t('Your submission is too fast. Please try again later.'));
//      }

      // 验证通过，记录用户ID以便提交时使用.
      $form_state->set('uid', $user->id());
    }
  }

  /**
   * 表单提交处理.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $step = $form_state->get('step') ?? 'request';

    if ($step === 'request') {
      // 请求验证码阶段.
      $email = $form_state->getValue('email');
      $users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties(['mail' => $email]);
      $user = reset($users);

      // 生成6位数验证码.
      $code = rand(100000, 999999);

      // 存储验证码和过期时间（15分钟后）.
      \Drupal::database()->merge('email_login_codes')
        ->key(['uid' => $user->id()])
        ->fields([
          'code' => $code,
          'expires' => (new DrupalDateTime('+15 minutes'))->format('Y-m-d H:i:s'),
        ])
        ->execute();

      // 发送邮件.
      $module = 'email_login';
      $key = 'send_code';
      $to = $email;
      $params['code'] = $code;
      $params['username'] = $user->getDisplayName();
      $langcode = $user->getPreferredLangcode();
      $send = TRUE;

      $result = $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
      $notification = new Notification(Notification::NOTIFICATION_TYPE_EMAIL, 'hcp_add_delegates', $to, ['@code' => $code]);
      $this->notificationEmailServices->sendNotification($notification);

      if ($result['result'] !== TRUE) {
        $this->messenger->addError($this->t('发送验证码邮件失败。请稍后再试。'));
      }
      else {
        $this->messenger->addStatus($this->t('验证码已发送到您的电子邮件。'));
        // 记录当前步骤为验证阶段.
        $form_state->set('step', 'verify');
        // 保持电子邮件地址.
        $form_state->set('email', $email);
        // 重建表单.
        $form_state->setRebuild(TRUE);
        // 记录日志.
        \Drupal::logger('email_login')->info('验证码已发送给用户 @email.', ['@email' => $email]);
      }
    }
    elseif ($step === 'verify') {
      // 验证验证码阶段.
      $uid = $form_state->get('uid');
      $user = User::load($uid);

      if ($user) {
        user_login_finalize($user);
        $this->messenger->addStatus($this->t('登录成功！'));
        // 删除已使用的验证码.
        \Drupal::database()->delete('email_login_codes')
          ->condition('uid', $uid)
          ->execute();
        // 记录日志.
        \Drupal::logger('email_login')->info('用户 @uid 通过验证码登录成功。', ['@uid' => $uid]);
        // 重定向到首页或其他页面.
        $form_state->setRedirect('<front>');
      }
      else {
        $this->messenger->addError($this->t('用户不存在。'));
        \Drupal::logger('email_login')->error('尝试登录的用户 @uid 不存在。', ['@uid' => $uid]);
      }
    }
//    $form_state->setValue('submission_check', time());
  }

}
