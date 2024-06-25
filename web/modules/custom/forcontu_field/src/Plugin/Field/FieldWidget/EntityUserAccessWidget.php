<?php

declare(strict_types=1);

namespace Drupal\forcontu_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Defines the 'entity_user_access_w' field widget.
 *
 * @FieldWidget(
 *   id = "entity_user_access_w",
 *   label = @Translation("Entity User Access - Widget"),
 *   field_types = {"entity_user_access"},
 * )
 */
final class EntityUserAccessWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['userlist'] = [
      '#type' => 'select',
      '#title' => t('User'),
      '#description' => t('Select group members from the list.'),
      '#options' => [
        0 => t('Anonymous'),
        1 => t('Admin'),
        2 => t('foobar'),
        // This should be implemented in a better way!
      ],

    ];

    $element['passwordlist'] = [
      '#type' => 'password',
      '#title' => t('Password'),
      '#description' => t('Select a password for the user'),
    ];

    // Setting default value to all fields from above.
    $childs = Element::children($element);
    foreach ($childs as $child) {
      $element[$child]['#default_value'] = $items[$delta]->{$child} ?? NULL;
    }

    return $element;
  }

}
