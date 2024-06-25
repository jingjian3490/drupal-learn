<?php

namespace Drupal\forcontu_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'forcontu_entities_text' field widget.
 *
 * @FieldWidget(
 *   id = "forcontu_entities_text",
 *   label = @Translation("RGB value as #ffffff"),
 *   field_types = {"forcontu_entities_color"},
 * )
 */
final class TextWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state): array {
    $value = $items[$delta]->value ?? '#ffffff';
    $element += [
      '#type' => 'textfield',
      '#default_value' => $value,
      '#size' => 7,
      '#maxlength' => 7,
    ];
    return ['value' => $element];
  }

}
