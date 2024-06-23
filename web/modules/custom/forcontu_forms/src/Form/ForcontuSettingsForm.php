<?php

namespace Drupal\forcontu_forms\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure forcontu_forms settings for this site.
 */
class ForcontuSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'forcontu_forms_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['forcontu_forms.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('forcontu_forms.settings');

    // 获取所有内容类型的列表
    $types = node_type_get_names();

    $form['forcontu_forms_allowed_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content types allowed'),
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => $this->t('Select content types.'),
      '#required' => TRUE,
    ];

    $form['forcontu_forms_message'] = [
      '#type' => 'textarea',
      '#title' => t('Message'),
      '#cols' => 60,
      '#rows' => 5,
      '#default_value' => $config->get('message'),
    ];

    // Do not need action button in Config Form, because parent class will deal with.
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if ($form_state->getValue('example') === 'wrong') {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('The value is not correct.'),
    //     );
    //   }
    // @endcode
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $allowed_types = array_filter($form_state->getValue('forcontu_forms_allowed_types'));
    sort($allowed_types);

    $this->config('forcontu_forms.settings')
      ->set('allowed_types', $allowed_types)
      ->set('message', $form_state->getValue('forcontu_forms_message'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
