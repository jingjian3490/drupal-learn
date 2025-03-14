<?php

namespace Drupal\forcontu_views\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\field\Boolean;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;

/**
 * Provides BooleanIcon field handler.
 *
 * @ViewsField("boolean_icon")
 */
final class BooleanIcon extends Boolean {

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $new_format = ['icon' => [t('Icon')]];

    $this->formats = array_merge($this->formats, $new_format);
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions(): array {
    $options = parent::defineOptions();
    $options['type_icon_path_true'] = [
      'default' => '',
    ];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['type_icon_path_true'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Icon path'),
      '#default_value' => $this->options['type_icon_path_true'],
      '#description' => $this->t('URI format public:// or private://'),
      '#states' => [
        'visible' => [
          'select[name="options[type]"]' => [
            'value' => 'icon',
          ],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values): ?array {
    $value = $this->getValue($values);
    if ($this->options['type'] == 'icon') {
      $build = [
        '#theme' => 'image',
        '#uri' => $this->options['type_icon_path_true'],
        '#alt' => $this->t('Highlighted content'),
        '#title' => $this->t('Highlighted content'),
        '#width' => 32,
      ];

      return $value ? $build : NULL;
    }
    else {
      return parent::render($values);
    }
  }

}
