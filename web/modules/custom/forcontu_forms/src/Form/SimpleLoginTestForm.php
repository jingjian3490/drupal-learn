<?php

namespace Drupal\forcontu_forms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Input uid, login.
 */
class SimpleLoginTestForm extends FormBase {

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

    return $form;

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
