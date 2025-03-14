<?php

namespace Drupal\forcontu_ajax\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * T.
 */
class AutocompleteForm extends FormBase {

  /**
   * T.
   */
  public function getFormId() {
    return 'forcontu_ajax_autocomplete';
  }

  /**
   * T.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user'] = [
      '#type' => 'textfield',
      '#title' => 'Username',
      '#autocomplete_route_name' => 'forcontu_ajax.user_autocomplete',
      '#autocomplete_route_parameters' => ['role' => 'admin'],
    ];

    $form['selected_node'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'Select a content',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['article', 'page'],
      ],
    ];

    // ...
    return $form;
  }

  /**
   * T.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // ...
  }

}
