<?php

namespace Drupal\forcontu_files\Form;

use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Forcontu files form.
 */
final class Unmanaged extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'forcontu_files_unmanaged';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['upload'] = [
      '#type' => 'file',
      '#title' => $this->t('PDF File'),
      '#description' => $this->t('Upload a PDF File.'),
    ];
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Upload file'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $file_system = \Drupal::service('file_system');
    $directory = 'public://unmanaged';
    $file_system->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);

    $destination = $directory . '/unmanaged.pdf';

    if ($file_system->saveData($form_state->getValue('upload'), $destination)) {
      \Drupal::messenger()->addMessage($this->t('File Uploaded'));
    }
    else {
      \Drupal::messenger()->addMessage($this->t('Error'));
    }
  }

}
