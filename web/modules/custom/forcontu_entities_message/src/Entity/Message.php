<?php

namespace Drupal\forcontu_entities_message\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\forcontu_entities_message\MessageInterface;
use Drupal\user\EntityOwnerTrait;
use Drupal\user\UserInterface;

/**
 * Defines the message entity class.
 *
 * @ContentEntityType(
 *   id = "forcontu_entities_message",
 *   label = @Translation("Message"),
 *   label_collection = @Translation("Messages"),
 *   label_singular = @Translation("message"),
 *   label_plural = @Translation("messages"),
 *   label_count = @PluralTranslation(
 *     singular = "@count messages",
 *     plural = "@count messages",
 *   ),
 *   bundle_label = @Translation("Message type"),
 *   handlers = {
 *     "list_builder" = "Drupal\forcontu_entities_message\MessageListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\forcontu_entities_message\MessageAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\forcontu_entities_message\Form\MessageForm",
 *       "edit" = "Drupal\forcontu_entities_message\Form\MessageForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "forcontu_entities_message",
 *   admin_permission = "administer forcontu_entities_message types",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "bundle",
 *     "label" = "subject",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/forcontu-entities-message",
 *     "add-form" = "/forcontu-entities-message/add/{forcontu_entities_message_type}",
 *     "add-page" = "/forcontu-entities-message/add",
 *     "canonical" = "/forcontu-entities-message/{forcontu_entities_message}",
 *     "edit-form" = "/forcontu-entities-message/{forcontu_entities_message}/edit",
 *     "delete-form" = "/forcontu-entities-message/{forcontu_entities_message}/delete",
 *     "delete-multiple-form" = "/admin/content/forcontu-entities-message/delete-multiple",
 *   },
 *   bundle_entity_type = "forcontu_entities_message_type",
 *   field_ui_base_route = "entity.forcontu_entities_message_type.edit_form",
 * )
 */
final class Message extends ContentEntityBase implements MessageInterface {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage): void {
    parent::preSave($storage);
    if (!$this->getOwnerId()) {
      // If no owner has been set explicitly, make the anonymous user the owner.
      $this->setOwnerId(0);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * T.
   */
  public function getType() {
    return $this->bundle();
  }

  /**
   * T.
   */
  public function getSubject() {
    return $this->get('subject')->value;
  }

  /**
   * T.
   */
  public function setSubject($subject) {
    $this->set('subject', $subject);
    return $this;
  }

  /**
   * T.
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * T.
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * T.
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * T.
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * T.
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * T.
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * T.
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * T.
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * T.
   */
  public function getUserToId() {
    return $this->get('user_to')->target_id;
  }

  /**
   * T.
   */
  public function setUserToId($uid) {
    $this->set('user_to', $uid);
    return $this;
  }

  /**
   * T.
   */
  public function getUserTo() {
    return $this->get('user_to')->entity;
  }

  /**
   * T.
   */
  public function setUserTo(UserInterface $account) {
    $this->set('user_to', $account->id());
    return $this;
  }

  /**
   * T.
   */
  public function getContent() {
    return $this->get('content')->value;
  }

  /**
   * T.
   */
  public function setContent($content) {
    $this->set('content', $content);
    return $this;
  }

  /**
   * T.
   */
  public function isRead() {
    return (bool) $this->getEntityKey('is_read');
  }

  /**
   * T.
   */
  public function setRead($read) {
    $this->set('is_read', $read ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Message entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_to'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('To'))
      ->setDescription(t('The user ID of the Message recipient.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      // setDisplayOptions
//      ->setDisplayOptions('view', [
//        'label' => 'To',
//        'type' => 'author',
//        'weight' => 0,
//      ])
//      ->setDisplayOptions('form', [
//        'type' => 'entity_reference_autocomplete',
//        'weight' => 5,
//        'settings' => [
//          'match_operator' => 'CONTAINS',
//          'size' => '60',
//          'autocomplete_type' => 'tags',
//          'placeholder' => '',
//        ],
//      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['subject'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Subject'))
      ->setDescription(t('The subject of the Message entity.'))
      ->setSettings([
        'max_length' => 100,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['content'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Content'))
      ->setDescription(t('The content of the Message'))
      ->setTranslatable(TRUE)
//      ->setDisplayOptions('view', [
//        'label' => 'hidden',
//        'type' => 'text_default',
//        'weight' => 0,
//      ])
      ->setDisplayConfigurable('view', TRUE)
//      ->setDisplayOptions('form', [
//        'type' => 'text_textfield',
//        'weight' => 0,
//      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['is_read'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Read'))
      ->setDescription(t('A boolean indicating whether the Message is read.'))
      ->setDefaultValue(FALSE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Message is published.'))
      ->setDefaultValue(TRUE);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Author'))
      ->setSetting('target_type', 'user')
      ->setDefaultValueCallback(self::class . '::getDefaultEntityOwner')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the message was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the message was last edited.'));

    return $fields;
  }

}
