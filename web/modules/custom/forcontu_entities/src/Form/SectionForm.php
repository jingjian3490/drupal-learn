<?php

namespace Drupal\forcontu_entities\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\forcontu_entities\Entity\Section;

/**
 * Section form.
 */
final class SectionForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {

    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t("Label for the Section."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => [Section::class, 'load'],
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['urlPattern'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL pattern'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->getUrlPattern(),
      '#description' => $this->t("URL pattern for the Section."),
      '#required' => TRUE,
    ];

    $form['color'] = [
      '#type' => 'color',
      '#title' => $this->t('Color'),
      '#default_value' => $this->entity->getColor(),
      '#description' => $this->t("Color for the Section."),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {

    $result = parent::save($form, $form_state);

    $message_args = ['%label' => $this->entity->label()];
    $this->messenger()->addStatus(
      match($result) {
        \SAVED_NEW => $this->t('Created new Section %label.', $message_args),
        \SAVED_UPDATED => $this->t('Updated Section %label.', $message_args),
      }
    );
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
