<?php

namespace Drupal\forcontu_field\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'forcontu_entities_color' field type.
 *
 * @FieldType(
 *   id = "forcontu_entities_color",
 *   label = @Translation("Forcontu Color"),
 *   description = @Translation("Some description."),
 *   default_widget = "string_textfield",
 *   default_formatter = "string",
 * )
 */
final class ColorItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    return match ($this->get('value')->getValue()) {
      NULL, '' => TRUE,
      default => FALSE,
    };
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {

    // @DCG
    // See /core/lib/Drupal/Core/TypedData/Plugin/DataType directory for
    // available data types.
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Hex value'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints(): array {
    $constraints = parent::getConstraints();

    $constraint_manager = $this->getTypedDataManager()->getValidationConstraintManager();

    // @DCG Suppose our value must not be longer than 10 characters.
    $options['value']['Length']['max'] = 10;

    // @DCG
    // See /core/lib/Drupal/Core/Validation/Plugin/Validation/Constraint
    // directory for available constraints.
    $constraints[] = $constraint_manager->create('ComplexData', $options);
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array {

    $columns = [
      'value' => [
        'type' => 'text',
        'size' => 'tiny',
        'not null' => FALSE,
      ],
    ];

    $schema = [
      'columns' => $columns,
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition): array {
    $random = new Random();
    $values['value'] = $random->word(mt_rand(1, 50));
    return $values;
  }

}
