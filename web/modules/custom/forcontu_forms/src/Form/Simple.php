<?php

namespace Drupal\forcontu_forms\Form;

use Drupal\Component\Serialization\Json;
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
      '#default_value' => 5,
      '#description' => $this->t('Choose a color.'),
    ];

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('Your username.'),
      '#default_value' => $this->currentUser->getAccountName(),
      '#required' => TRUE,
    ];

    $color = [
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
    $username = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('Your username.'),
      '#default_value' => $this->currentUser->getAccountName(),
      '#required' => TRUE,
    ];

    $form['container'] = [
      '#type' => 'details',
      '#title' => 'Container',
    ];
    $form['container']['color1'] = $color;
    $form['container']['username'] = $username;

    $element = [];
    $element += [
      '#type' => 'details',
      '#title' => 'Element',
    ];

    $element += [
      'color2' => $color,
      'username' => $username,
    ];
    $form['field_color'] = $element;

    $form['fields_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'med-form-fields-wrapper'],
    ];

    $form['fields_wrapper']['switched_to'] = [
      '#type' => 'radios',
      '#title' => $this->t('<span class="form-required">Have you switched to another treatment?</span>'),
      '#options' => [
        1 => $this->t('Yes'),
        0 => $this->t('No'),
      ],
      '#attributes' => [
        'class' => ['drupal-ajax'],
      ],
    ];

    // $form['fields_wrapper']['field_previous_treatment'] = [
    //      '#type' => 'container',
    //      '#children' => 'Test text 1',
    //      '#states' => [
    //        'visible' => [
    //          ':input[name="switched_to"]' => ['value' => 1],
    //        ],
    //      ],
    //    ];
    //    $form['fields_wrapper']['field_previous_treatment_no'] = [
    //      '#type' => 'container',
    //      '#children' => 'Test text 2',
    //      '#states' => [
    //        'visible' => [
    //          ':input[name="switched_to"]' => ['value' => 0],
    //        ],
    //      ],
    //    ];
    //    $form['field_previous_treatment'] = [
    //      '#type' => 'container',
    //      '#children' => 'Test text 1',
    //      '#states' => [
    //        'visible' => [
    //          ':input[name="switched_to"]' => ['value' => 5],
    //        ],
    //      ],
    //    ];
    //    $form['field_previous_treatment_no'] = [
    //      '#type' => 'container',
    //      '#children' => 'Test text 2',
    //      '#states' => [
    //        'visible' => [
    //          ':input[name="switched_to"]' => ['value' => 5],
    //        ],
    //      ],
    //    ];
    $form['#attached']['library'][] = 'core/drupal.states';
    $form['field_previous_treatment1'] = [
      '#type' => 'item',
      '#markup' => 'Test text 11',
      '#wrapper_attributes' => [
        'data-drupal-states' => Json::encode([
          'visible' => [
            ':input[name="switched_to"]' => ['value' => 1],
          ],
        ]),
      ],
    ];

    $form['field_previous_treatment_no1'] = [
      '#type' => 'item',
      '#markup' => 'Test text 21',
      '#wrapper_attributes' => [
        'data-drupal-states' => Json::encode([
          'visible' => [
            ':input[name="switched_to"]' => ['value' => 0],
          ],
        ]),
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    $arr = [1, 2, 3, 4];
    foreach ($arr as &$value) {
      $value = $value * 2;
    }

    unset($value);
    foreach ($arr as $key => $value) {
      echo "{$key} => {$value} ";
      print_r($arr);
      $form[$key] = [
        '#type' => 'textfield',
        '#title' => $key,
        '#default_value' => $value,
      ];
      $form['arr' . $key] = [
        '#type' => 'markup',
        '#markup' => '<pre>' . print_r($arr, TRUE) . '</pre>',
      ];
    }

    $aaa = 4;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $title = $form_state->getValue('title');
    if (strlen($title) == 5) {
      // Set an error for the form element with a key of "title".
      $form_state->setErrorByName('title', $this->t('The title must be at least 5 characters long.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    if ($form_state->getValue('title') != '1') {
      $color = $form_state->getValue('color');
      $color1 = $form_state->getValue('color1');
      $color2 = $form_state->getValue('color2');
      $this->messenger()->addStatus($color . ' 0');
      $this->messenger()->addStatus($color1 . ' 1');
      $this->messenger()->addStatus($color2 . ' 2');
      return;
    }

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

    $this->messenger()
      ->addMessage($this->t('The form has been submitted correctly'));

    $this->logger('forcontu_forms')
      ->notice('New Simple Form entry from user %username inserted: %title.', [
        '%username' => $form_state->getValue('username'),
        '%title' => $form_state->getValue('title'),
      ]);

    $form_state->setRedirect('forcontu_pages.simple');
  }

}
