<?php

namespace Drupal\forcontu_files\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\file\FileUsage\DatabaseFileUsageBackend;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Forcontu Files form.
 */
final class Managed extends FormBase {

  /**
   * AccountInterface.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * DatabaseFileUsageBackend.
   *
   * @var \Drupal\file\FileUsage\DatabaseFileUsageBackend
   */
  protected $fileUsage;

  public function __construct(AccountInterface $current_user, DatabaseFileUsageBackend $file_usage) {
    $this->currentUser = $current_user;
    $this->fileUsage = $file_usage;
  }

  /**
   * T.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('file.usage')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'forcontu_files_managed';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['upload'] = [
      '#title' => $this->t('Upload file'),
      '#type' => 'managed_file',
//      '#upload_location' => 'public://managed',
      '#upload_location' => 'private://pdf',
      '#upload_validators' => [
        'file_validate_extensions' => ['pdf'],
      ],
      '#required' => TRUE,
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
    $file_storage = \Drupal::entityTypeManager()->getStorage('file');
    foreach ($form_state->getValue('upload') as $fid) {
      $file = $file_storage->load($fid);
      $this->fileUsage->add($file, 'forcontu_files', 'user', $this->currentUser->id(), 1);
      \Drupal::messenger()->addMessage($this->t('File Uploaded'));
    }
  }

}
