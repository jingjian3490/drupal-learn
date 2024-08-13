<?php

namespace Drupal\forcontu_ajax\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * T.
 */
class AjaxForm extends FormBase {

  /**
   * T.
   */
  public array $colors = [
    'warm' => [
      'red' => 'Red',
      'orange' => 'Orange',
      'yellow' => 'Yellow',
    ],
    'cool' => [
      'blue' => 'Blue',
      'purple' => 'Purple',
      'green' => 'Green',
    ],
  ];

  /**
   * T.
   */
  public function getFormId(): string {
    return 'forcontu_ajax_ajax_form';
  }

  /**
   * T.
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['temperature'] = [
      '#title' => $this->t('Temperature'),
      '#type' => 'select',
      '#options' => ['warm' => 'Warm', 'cool' => 'Cool'],
      '#empty_option' => $this->t('-select-'),
      '#ajax' => [
        'callback' => '::colorCallback',
        'wrapper' => 'color-wrapper',
      ],
    ];

    // 禁用表单缓存.
    $form_state->setCached(FALSE);

    $form['color_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'color-wrapper'],
    ];

    $form['color_wrapper']['cool'] = [
      '#type' => 'select',
      '#title' => $this->t('Coor'),
      '#options' => $this->colors['cool'],
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
   * T.
   */
  public function colorCallback(array &$form, FormStateInterface $form_state) {
    $temperature = $form_state->getValue('temperature');
    $form['color_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'color-wrapper'],
    ];
    $form['color_wrapper']['color'] = [
      '#type' => 'select',
      '#title' => $this->t('Color'),
      '#options' => $this->colors[$temperature],
    ];
    return $form['color_wrapper'];
  }

  /**
   * T.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // 处理提交逻辑.
  }

}
