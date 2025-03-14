<?php

namespace Drupal\forcontu_theming\Element;

use Drupal\Core\Render\Element\RenderElement;

/**
 * Provides a render element to display a forcontu theming dimensions.
 *
 * Properties:
 * - #foo: Property description here.
 *
 * Usage Example:
 * @code
 * $build['forcontu_theming_dimensions'] = [
 *   '#type' => 'forcontu_theming_dimensions',
 *   '#foo' => 'Some value.',
 * ];
 * @endcode
 *
 * @RenderElement("custom_render_element")
 */
class ForcontuThemingDimensions extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#pre_render' => [
        [$class, 'preRenderForcontuThemingDimensions'],
      ],
      '#length' => NULL,
      '#width' => NULL,
      '#height' => NULL,
      '#unit' => 'cm.',
      '#theme' => 'custom_render_element',
    ];
  }

  /**
   * Forcontu theming dimensions element pre render callback.
   *
   * @param array $element
   *   An associative array containing the properties of
   *   the forcontu_theming_dimensions element.
   *
   * @return array
   *   The modified element.
   */

  /**
   * Element pre render callback.
   */
  public static function preRenderForcontuThemingDimensions($element) {
    return $element;
  }

}
