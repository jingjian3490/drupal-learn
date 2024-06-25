<?php

namespace Drupal\forcontu_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'Simple text-based formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "forcontu_entities_simple_text",
 *   label = @Translation("Simple text-based formatter"),
 *   field_types = {"forcontu_entities_color"},
 * )
 */
final class SimpleTextFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements1(FieldItemListInterface $items, $langcode): array {
    $element = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        // We create a render array to produce the desired markup,
        // "<p style="color: #hexcolor">The color code ...
        // #hexcolor</p>".
        // See theme_html_tag().
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'style' => 'color: ' . $item->value,
        ],
        '#value' => $this->t('The color code in this field is @code', ['@code' => $item->value]),
      ];
    }
    return $element;
  }

  /**
   * T.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'html_tag',
        '#tag' => $this->getSetting('formatter_tag'),
        '#attributes' => [
          'style' => 'color: ' . $item->value,
        ],
        '#value' => $this->t('The color code in this field is @code', ['@code' => $item->value]),
      ];
    }
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $output['formatter_tag'] = [
      '#title' => $this->t('HTML tag'),
      '#type' => 'select',
      '#options' => [
        'p' => $this->t('p'),
        'div' => $this->t('div'),
        'span' => $this->t('span'),
      ],
      '#default_value' => $this->getSetting('formatter_tag'),
    ];
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'formatter_tag' => 'p',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $formatter_tag = $this->getSetting('formatter_tag');

    $summary[] = $this->t('HTML Tag: @tag', ['@tag' => $formatter_tag]);

    return $summary;
  }

}
