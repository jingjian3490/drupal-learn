<?php

namespace Drupal\forcontu_forms\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Forcontu forms form.
 */
class Simple extends FormBase {

  /**
   * Connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $database;

  /**
   * AccountInterface.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $currentUser;

  public function __construct(Connection $database, AccountInterface $current_user) {
    $this->database = $database;
    $this->currentUser = $current_user;
  }

  /**
   * T. `create()` 方法负责从服务容器中获取服务.
   */
  public static function create(ContainerInterface $container): Simple|static {
    return new static(
      $container->get('database'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'forcontu_forms_simple';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#description' => $this->t('The title must be at least 5 characters long.'),
      '#required' => TRUE,
    ];

    $form['color'] = [
      '#type' => 'select',
      '#title' => $this->t('Color'),
      '#options' => [
        0 => $this->t('Black'),
        1 => $this->t('Red'),
        2 => $this->t('Blue'),
        3 => $this->t('Green'),
        4 => $this->t('Orange'),
        5 => $this->t('White'),
      ],
      '#default_value' => 2,
      '#description' => $this->t('Choose a color.'),
    ];

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('Your username.'),
      '#default_value' => $this->currentUser->getAccountName(),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $title = $form_state->getValue('title');
    if (strlen($title) < 5) {
      // Set an error for the form element with a key of "title".
      $form_state->setErrorByName('title', $this->t('The title must be at least 5 characters long.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->database->insert('forcontu_forms_simple')
      ->fields([
        'title' => $form_state->getValue('title'),
        'color' => $form_state->getValue('color'),
        'username' => $form_state->getValue('username'),
        'email' => $this->currentUser->getEmail(),
        'uid' => $this->currentUser->id(),
        'ip' => \Drupal::request()->getClientIP(),
        'timestamp' => \Drupal::time()->getRequestTime(),
      ])
      ->execute();

    $this->messenger()->addMessage($this->t('The form has been submitted correctly'));

    $this->logger('forcontu_forms')->notice('New Simple Form entry from user %username inserted: %title.', [
      '%username' => $form_state->getValue('username'),
      '%title' => $form_state->getValue('title'),
    ]);

    $form_state->setRedirect('forcontu_pages.simple');
  }

}
