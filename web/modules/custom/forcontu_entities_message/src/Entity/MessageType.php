<?php

namespace Drupal\forcontu_entities_message\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Message type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "forcontu_entities_message_type",
 *   label = @Translation("Message type"),
 *   label_collection = @Translation("Message types"),
 *   label_singular = @Translation("message type"),
 *   label_plural = @Translation("messages types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count messages type",
 *     plural = "@count messages types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\forcontu_entities_message\Form\MessageTypeForm",
 *       "edit" = "Drupal\forcontu_entities_message\Form\MessageTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\forcontu_entities_message\MessageTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer forcontu_entities_message types",
 *   bundle_of = "forcontu_entities_message",
 *   config_prefix = "forcontu_entities_message_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/forcontu_entities_message_types/add",
 *     "edit-form" = "/admin/structure/forcontu_entities_message_types/manage/{forcontu_entities_message_type}",
 *     "delete-form" = "/admin/structure/forcontu_entities_message_types/manage/{forcontu_entities_message_type}/delete",
 *     "collection" = "/admin/structure/forcontu_entities_message_types",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   },
 * )
 */
final class MessageType extends ConfigEntityBundleBase {

  /**
   * The machine name of this message type.
   */
  protected string $id;

  /**
   * The human-readable name of the message type.
   */
  protected string $label;

}
