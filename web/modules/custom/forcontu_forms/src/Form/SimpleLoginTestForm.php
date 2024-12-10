<?php

namespace Drupal\forcontu_forms\Form;

use Drupal\Core\Flood\FloodInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\honeypot\HoneypotService;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Input uid, login.
 */
class SimpleLoginTestForm extends FormBase {

  /**
   * Honeypot service.
   *
   * @var \Drupal\honeypot\HoneypotService
   */
  protected HoneypotService $honeypotService;

  protected $flood;

  /**
   * 构造函数。.
   */
  public function __construct(
    HoneypotService $honeypotService,
    FloodInterface $flood,
  ) {
    $this->honeypotService = $honeypotService;
    $this->flood = $flood;
  }

  /**
   * 创建表单时的依赖注入。.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('honeypot'),
      $container->get('flood'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'forcontu_forms_simple_login_test_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ID'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    // $this->honeypotService->addFormProtection(
    //      $form,
    //      $form_state,
    //      ['honeypot', 'time_restriction']
    //    );
    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $ip = \Drupal::request()->getClientIp();
    $flood_identifier_ip = 'otp_request_' . $ip;
    $flood_identifier_ip_email = 'otp_request_' . $ip . $form_state->getValue('id');

    // 限制每IP每小时最多5次请求.
    if (!$this->flood->isAllowed('1anonymous_otp_request_ip', 5, 3600, $flood_identifier_ip)) {
      $form_state->setErrorByName('id', 'anonymous_otp_request_ip 5');
    }
    $this->flood->register('1anonymous_otp_request_ip', 3600, $flood_identifier_ip);

    // 限制每IP每小时最多5次请求.
    if (!$this->flood->isAllowed('1anonymous_otp_request_ip_email', 2, 3600, $flood_identifier_ip_email)) {
      $form_state->setErrorByName('id', 'anonymous_otp_request_ip_email 2');
    }
    $this->flood->register('1anonymous_otp_request_ip_email', 3600, $flood_identifier_ip_email);

    // 限制所有匿名用户每小时总共最多100次请求.
    if (!$this->flood->isAllowed('1global_anonymous_otp_request', 10, 3600)) {
      $form_state->setErrorByName('id', 'global_anonymous_otp_request 10');
    }
    $this->flood->register('1global_anonymous_otp_request', 3600);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $current_user = \Drupal::currentUser();
    if ($current_user->isAuthenticated()) {
      $this->messenger()->addMessage($this->t('You are logged in.'));
      $form_state->setRedirect('<front>');
      return;
    }
    $id = $form_state->getValue('id');
    $user = User::load($id);
    if ($user) {
      user_login_finalize($user);
      $this->messenger()->addMessage($this->t('Welcome logged in.'));
      $form_state->setRedirect('<front>');
    }

    $this->messenger()->addMessage($this->t('Please input your ID.'));

  }

}
