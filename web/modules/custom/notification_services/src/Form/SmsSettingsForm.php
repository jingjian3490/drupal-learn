<?php

namespace Drupal\notification_services\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure SMS settings for this site.
 */
class SmsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'notification_services_sms_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['notification_services.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('notification_services.settings');

    $form['sms_api_url'] = [
      '#type' => 'url',
      '#title' => $this->t('SMS API URL'),
      '#default_value' => $config->get('sms_api_url'),
      '#required' => TRUE,
    ];

    $form['sms_api_account'] = [
      '#type' => 'textfield',
      '#title' => $this->t('SMS API Account'),
      '#default_value' => '',
      '#required' => TRUE,
    ];

    $form['sms_api_password'] = [
      '#type' => 'password',
      '#title' => $this->t('SMS API Password'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('notification_services.settings');

    $config->set('sms_api_url', $form_state->getValue('sms_api_url'));

    $credentials = [
      'account' => $form_state->getValue('sms_api_account'),
      'password' => $form_state->getValue('sms_api_password'),
    ];

    $encrypted_credentials = base64_encode(json_encode($credentials));
    $config->set('sms_api_credentials', $encrypted_credentials);

    $config->save();

    parent::submitForm($form, $form_state);
  }

}
